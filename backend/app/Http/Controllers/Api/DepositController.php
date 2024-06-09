<?php

namespace App\Http\Controllers\Api;

use App\Exception\Http\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Services\DepositService;

use Illuminate\Support\Facades\Log;

use Throwable;

class DepositController extends Controller
{
    private DepositService $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    public function createDeposit(Request $request): JsonResponse
    {
        try {
            $userId = 'e61796f8-4aaa-4bfe-b29a-34cf284b4276'; // TODO: we must use auth, here

            $this->depositService->create($userId, $request->all());

            return response()->json([
                'message' => 'success'
            ]);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->message
            ], $e->statusCode);
        } catch (Throwable $t) {
            Log::error($t);
            return response()->json([
                'message' => 'internal server error'
            ]);
        }
    }
}
