<?php

namespace App\Services;

use App\Exception\Http\BadRequestException;
use Illuminate\Support\Facades\Validator;

class DepositService
{
    // genereate an deposit order
    public function create(array $requestBody)
    {
        $validator = Validator::make($requestBody, [
            'customer.name' => ['required', 'string'],

            'transaction.order_id' => ['required', 'string'],
            'transaction.amount' => ['required', 'decimal:2'],
        ]);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }

        // TODO: store database and make status as 'create'
    }

    // make a payment from the given order id
    public function pay(array $requestBody)
    {
        // TODO: Implement me
    }
}
