<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    use HasFactory;

    protected $table = 'balance_histories';
    protected $fillable = [
        'uid',
        'user_id',
        'transaction_order_id',
        'amount',
        'updated_at'
    ];
}
