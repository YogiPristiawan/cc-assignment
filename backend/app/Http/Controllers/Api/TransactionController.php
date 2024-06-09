<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getHistories(Request $request)
    {
        try {
            $userId = 'e61796f8-4aaa-4bfe-b29a-34cf284b4276'; // TODO: change this

            $transactions = $this->transactionService->getHistories($userId);

            return response()->json([
                'message' => 'transactions data',
                'data' => $transactions
            ]);
        } catch (Throwable $t) {
            Log::error($t);
            return response()->json([
                'message' => 'internal server error'
            ]);
        }
    }
}
