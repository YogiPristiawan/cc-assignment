<?php

namespace App\Services;

use App\Exception\Http\BadRequestException;
use Illuminate\Support\Facades\Validator;

class WithdrawService
{
    // make a withdraw order
    public function create(array $requestBody)
    {
        $validator = Validator::make($requestBody, [
            'customer.name' => ['required', 'string'],

            'transaction.amount' => ['required', 'decimal:2']
        ]);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }

        // TODO: store database and obtain balance id

        // TODO: store into job && call payment gateway
    }

    // integrate with third party e.g. bank, e-wallet
    public function payout(array $request)
    {
        // TODO: make payout
    }
}
