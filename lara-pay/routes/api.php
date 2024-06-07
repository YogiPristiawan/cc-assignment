<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepositController;

Route::post('/deposit', [DepositController::class, 'createDeposit']);
