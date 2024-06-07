<?php

use Illuminate\Support\Facades\Route;

Route::post('/deposit', function () {
    return response()->json([
        'message' => 'success'
    ]);
});
