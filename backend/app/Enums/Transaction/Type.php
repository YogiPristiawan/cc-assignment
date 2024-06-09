<?php

namespace App\Enums\Transaction;

enum Type: int
{
    case Deposit = 1;
    case Withdraw = 2;
}
