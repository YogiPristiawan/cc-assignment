<?php

namespace App\Services;

use App\Exception\Http\BadRequestException;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\User;
use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\Type as TransactionType;
use App\Exception\Http\InternalServerError;
use App\Exception\Http\NotFoundException;
use App\Lib\PaymentSdk;
use App\Models\BalanceHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessBalance;
use App\Jobs\Params\ProcessBalanceParam;
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

        // get the user detail
        $user = User::where('id', $userId)->first(['name']);
        if (!$user) throw new NotFoundException('user not found');

        // crate a transaction history
        $paymentChargeSuccess = false;
        $transaction = null;
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
                    'order_id' => (string)$transaction->order_id,
                    'amount' => $payload['transaction']['amount']
                ],
                'timestamp' => $transaction->created_at->format(DateTime::RFC3339)
            ]);
            if ($paymentResponse['status'] === 1) {
                $paymentChargeSuccess = true;
            }

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }
        if (!$paymentChargeSuccess && $transaction !== null) {
            Transaction::where('order_id', (string)$transaction->order_id)->update([
                'status' => TransactionStatus::Failed
            ]);
            throw new InternalServerError('failed to create deposit');
        }

        // update balance asynchronously
        if ($paymentChargeSuccess && $transaction !== null) {
            $this->updateBalance([
                'transaction_order_id' => $transaction->order_id,
                'amount' => $payload['transaction']['amount'],
                'user_id' => $userId
            ]);
        }
    }

    // update customer balance for the successful payment.
    // in the real case this function is used to handle the Payment Gateway webhook/callback url
    public function updateBalance(array $args)
    {
        $validator = Validator::make($args, [
            'transaction_order_id' => ['required', 'string', 'uuid'],
            'user_id' => ['required', 'string', 'uuid'],
            'amount' => ['required', 'string', 'decimal:2']
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
        $validatedArgs = $validator->validated();

        $processBalanceParam = new ProcessBalanceParam([
            'transaction_type' => TransactionType::Deposit,
            'transaction_order_id' => $validatedArgs['transaction_order_id'],
            'user_id' => $validatedArgs['user_id'],
            'amount' => $validatedArgs['amount']
        ]);
        ProcessBalance::dispatch($processBalanceParam);
    }
}
