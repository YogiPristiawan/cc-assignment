<?php

namespace App\Lib;

use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Http;
use Exception;

class PaymentSdk
{
    /**
     * @return array<string,mixed>
     * @param array<int,mixed> $payload
     * @param array<int,mixed> $args
     */
    public function charge(array $args): array
    {
        $validator = Validator::make($args, [
            'customer.name' => ['required', 'string'],
            'transaction.order_id' => ['required', 'string'],
            'transaction.amount' => ['required', 'decimal:2', 'gt:0'],
            'timestamp' => ['required', 'string', 'date_format:Y-m-d\\TH:i:sP'],
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
        $validatedArgs = $validator->validated();

        // NOTE: we should make a HTTP request to the payment gateway here.
        // but, for simplicity we just return it immediately
        //
        // $token = base64_encode($validatedPayload['customer']['name']);
        // $response = Http::withToken($token, 'Bearer')->post("https://yourdomain.com/deposit", [
        //     'order_id' => $validatedPayload['transaction']['order_id'],
        //     'amount' => (float)$validatedPayload['transaction']['amount'],
        //     'timestamp' => $validatedPayload['timestamp']
        // ]);

        // NOTE: here, we should return the actual respose data from the HTTP response above
        return [
            'order_id' => $validatedArgs['transaction']['order_id'],
            'amount' => (float)$validatedArgs['transaction']['amount'],
            'status' => 1
        ];
    }
    /**
     * @return array<string,mixed>
     * @param array<int,mixed> $args
     */
    public function payout(array $args): array
    {
        $validator = Validator::make($args, [
            'customer.name' => ['required', 'string'],
            'transaction.order_id' => ['required', 'string'],
            'transaction.amount' => ['required', 'decimal:2', 'gt:0'],
            'timestamp' => ['required', 'string', 'date_format:Y-m-d\\TH:i:sP'],
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
        $validatedArgs = $validator->validated();

        // NOTE: we should make a HTTP request to the payment gateway here.
        // but, for simplicity we just return it immediately
        //
        // $token = base64_encode($validatedPayload['customer']['name']);
        // $response = Http::withToken($token, 'Bearer')->post("https://yourdomain.com/deposit", [
        //     'order_id' => $validatedPayload['transaction']['order_id'],
        //     'amount' => (float)$validatedPayload['transaction']['amount'],
        //     'timestamp' => $validatedPayload['timestamp']
        // ]);

        // NOTE: here, we should return the actual response data from the HTTP response above
        return [
            'order_id' => $validatedArgs['transaction']['order_id'],
            'amont' => (float)$validatedArgs['transaction']['amount'],
            'status' => 1
        ];
    }
}
