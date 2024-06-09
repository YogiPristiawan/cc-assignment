<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\BalanceController;

Route::post('/deposit', [DepositController::class, 'createDeposit']);
Route::post('/withdraw', [WithdrawController::class, 'createWithdraw']);
Route::get('/transactions', [TransactionController::class, 'getHistories']);
Route::get('/current-balance', [BalanceController::class, 'getCurrentBalance']);
