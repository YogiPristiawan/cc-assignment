<?php

namespace App\Enums\Transaction;

enum Status: int
{
    case Prepare = 1;
    case Pending = 2;
    case Success = 3;
    case Failed = 4;
}
