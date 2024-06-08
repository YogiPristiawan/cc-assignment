<?php

namespace App\Services;

use App\Exception\Http\BadRequestException;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\User;
use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\Type as TransactionType;
use App\Exception\Http\NotFoundException;
use App\Lib\PaymentSdk;
use App\Models\BalanceHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;
use DateTime;

class DepositService
{
    private PaymentSdk $paymentSdk;

    public function __construct(PaymentSdk $paymentSdk)
    {
        $this->paymentSdk = $paymentSdk;
    }

    // genereate an deposit order
    public function create(string $userId, array $requestBody)
    {
        $validator = Validator::make($requestBody, [
            'transaction.amount' => ['required', 'string', 'decimal:2', 'gt:0'],
        ]);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }
        $payload = $validator->validated();
        $amount = (float)$payload['transaction']['amount'];

        // get the user detail
        $user = User::where('id', $userId)->first(['name']);
        if (!$user) throw new NotFoundException('user not found');

        // crate a transaction history
        $shouldUpdateBalance = false;
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'order_id' => (string)Str::uuid(),
                'user_id' => $userId,
                'amount' => $payload['transaction']['amount'],
                'status' => TransactionStatus::Pending,
                'type' => TransactionType::Deposit
            ]);

            // make a charge to the Payment Gateway
            $paymentResponse = $this->paymentSdk->charge([
                'customer' => [
                    'name' => $user->name
                ],
                'transaction' => [
                    'order_id' => $transaction->order_id,
                    'amount' => $payload['transaction']['amount']
                ],
                'timestamp' => $transaction->created_at->format(DateTime::RFC3339)
            ]);
            if ($paymentResponse['status'] === 1) {
                $shouldUpdateBalance = true;
            }

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        // update balance asynchronously
        if ($shouldUpdateBalance) {
            $this->updateBalance([
                'transaction_order_id' => $transaction->order_id,
                'amount' => $payload['transaction']['amount'],
                'user_uid' => $userId
            ]);
        }
    }

    // update customer balance for the successful payment.
    // in the real case this function is used to handle the Payment Gateway webhook/callback url
    public function updateBalance(array $args)
    {
        $validator = Validator::make($args, [
            'transaction_order_id' => ['required', 'string', 'uuid'],
            'user_uid' => ['required', 'string', 'uuid'],
            'amount' => ['required', 'string', 'decimal:2']
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
        $validatedArgs = $validator->validated();

        $maxAttempt = 3;
        $attemptRemaining = $maxAttempt;
        while ($attemptRemaining > 0) {
            DB::beginTransaction();
            try {
                DB::statement("SET TRANSACTION ISOLATION LEVEL REPEATABLE READ");
                $user = User::where('id', $validatedArgs['user_uid'])->first(['current_balance']);

                BalanceHistory::create([
                    'transaction_order_id' => $validatedArgs['transaction_order_id'],
                    'user_uid' => $validatedArgs['user_uid'],
                    'amount' => (float)$validatedArgs['amount']
                ]);

                User::where('id', $validatedArgs['user_uid'])->update([
                    'current_balance' => $user->current_balance + (float)$validatedArgs['amount']
                ]);
                DB::commit();

                return;
            } catch (Throwable $t) {
                DB::rollBack();

                $attemptRemaining--;
                if ($attemptRemaining == 0) {
                    throw $t;
                }
            }

            sleep(($maxAttempt - $attemptRemaining) * 2);
        }
    }
}
