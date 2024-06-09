<?php

namespace App\Http\Controllers\Api;

use App\Exception\Http\HttpException;
use App\Http\Controllers\Controller;
use App\Services\BalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Throwable;

class BalanceController extends Controller
{
    private BalanceService $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }
    /**
     * @return JsonResponse
     */
    public function getCurrentBalance(): JsonResponse
    {
        try {
            $userId = 'e61796f8-4aaa-4bfe-b29a-34cf284b4276'; // TODO: we must use auth, here

            $balance = $this->balanceService->getCurrentBalance($userId);

            return response()->json([
                'message' => 'success',
                'data' => [
                    'current_balance' => $balance
                ]
            ]);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->message
            ], $e->statusCode);
        } catch (Throwable $t) {
            Log::error($t);
            return response()->json([
                'message' => 'internal server error'
            ], 500);
        }
    }
}
