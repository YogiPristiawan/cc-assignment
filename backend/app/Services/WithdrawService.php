<?php

namespace App\Services;

use App\Exception\Http\BadRequestException;
use App\Exception\Http\InternalServerError;
use App\Exception\Http\NotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\Type as TransactionType;
use App\Lib\PaymentSdk;
use App\Models\Transaction;
use App\Models\BalanceHistory;
use App\Jobs\ProcessBalance;
use App\Jobs\Params\ProcessBalanceParam;

use Exception;
use Throwable;
use DateTime;

class WithdrawService
{
    private PaymentSdk $paymentSdk;

    public function __construct(PaymentSdk $paymentSdk)
    {
        $this->paymentSdk = $paymentSdk;
    }

    // make a withdraw order
    public function create(string $userId, array $requestBody)
    {
        $validator = Validator::make($requestBody, [
            'transaction.amount' => ['required', 'decimal:2', 'gt:0']
        ]);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }
        $validatedReqBody = $validator->validated();

        // get the user detail
        $user = User::where('id', $userId)->first(['name']);
        if (!$user) throw new NotFoundException('user not found');

        // valiate balance
        $balance = BalanceHistory::where('user_id', $userId)->sum('amount');
        if ((float)$balance < (float)$validatedReqBody['transaction']['amount']) throw new BadRequestException("insufficicent balance");


        // create a withdraw transaction history
        $payoutChargeSuccess = false;
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'order_id' => (string)Str::uuid(),
                'user_id' => $userId,
                'amount' => $validatedReqBody['transaction']['amount'],
                'status' => TransactionStatus::Pending,
                'type' => TransactionType::Withdraw
            ]);

            // make a payout to the Payment Gateway
            $payoutResponse = $this->paymentSdk->payout([
                'customer' => [
                    'name' => $user->name
                ],
                'transaction' => [
                    'order_id' => (string)$transaction->order_id,
                    'amount' => $validatedReqBody['transaction']['amount'],
                ],
                'timestamp' => $transaction->created_at->format(DateTime::RFC3339)
            ]);
            if ($payoutResponse['status'] === 1) {
                $payoutChargeSuccess = true;
            }

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }
        if (!$payoutChargeSuccess && $transaction !== null) {
            Transaction::where('order_id', (string)$transaction->order_id)->update([
                'status' => TransactionStatus::Failed
            ]);
            throw new InternalServerError('failed to create deposit');
        }

        // update balance asynchronously
        if ($payoutChargeSuccess && $transaction !== null) {
            $this->updateBalance([
                'transaction_order_id' => $transaction->order_id,
                'amount' => $requestBody['transaction']['amount'],
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
            'transaction_type' => TransactionType::Withdraw,
            'transaction_order_id' => $validatedArgs['transaction_order_id'],
            'user_id' => $validatedArgs['user_id'],
            'amount' => $validatedArgs['amount']
        ]);
        ProcessBalance::dispatch($processBalanceParam);
    }
}
