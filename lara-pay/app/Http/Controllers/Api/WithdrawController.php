<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exception\Http\HttpException;
use App\Services\WithdrawService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Throwable;

class WithdrawController extends Controller
{
    private WithdrawService $withdrawService;

    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    public function createWithdraw(Request $request): JsonResponse
    {
        try {
            $userId = 'e61796f8-4aaa-4bfe-b29a-34cf284b4276'; // TODO: change this

            $this->withdrawService->create($userId, $request->all());

            return response()->json([
                'message' => 'sucess'
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
