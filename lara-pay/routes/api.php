<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\TransactionController;

Route::post('/deposit', [DepositController::class, 'createDeposit']);
Route::post('/withdraw', [WithdrawController::class, 'createWithdraw']);
Route::get('/transactions', [TransactionController::class, 'getHistories']);
