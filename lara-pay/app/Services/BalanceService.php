<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BalanceService
{
    /**
     * @return mixed
     */
    public function getCurrentBalance(string $userId)
    {
        $balance = DB::table('balance_histories')->where('user_id', $userId)->sum('amount');

        return $balance;
    }
}
