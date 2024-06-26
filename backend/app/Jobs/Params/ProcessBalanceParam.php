<?php

namespace App\Jobs\Params;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\Transaction\Type as TransactionType;
use Exception;

class ProcessBalanceParam
{
    public TransactionType $transactionType;
    public string $transactionOrderId;
    public string $userId;
    public string $amount;

    /**
     * @return void
     * @param array<int,mixed> $args
     */
    public function __construct(array $args)
    {
        $this->validateParam($args);

        $this->transactionType = $args['transaction_type'];
        $this->transactionOrderId = $args['transaction_order_id'];
        $this->userId = $args['user_id'];
        $this->amount = $args['amount'];
    }
    /**
     * @return void
     * @param array<int,mixed> $args
     */
    private function validateParam(array $args): void
    {
        $validator = Validator::make($args, [
            'transaction_type' => ['required', Rule::enum(TransactionType::class)],
            'transaction_order_id' => ['required', 'uuid'],
            'user_id' => ['required', 'uuid'],
            'amount' => ['required', 'string', 'decimal:2']
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors());
        }
    }
}
