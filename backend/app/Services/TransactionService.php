<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\Type as TransactionType;

class TransactionService
{
    /**
     * @return array
     */
    public function getHistories(string $userId): array
    {
        $transactions = DB::table('transactions')->where('user_id', $userId)->orderBy('id', 'DESC')->get([
            'order_id',
            'amount',
            'type',
            'status',
            'created_at'
        ]);
        if ($transactions->isEmpty()) return [];

        $returnedData = [];
        foreach ($transactions as $transaction) {
            $transactionStatus = null;
            switch ($transaction->status) {
                case TransactionStatus::Prepare->value:
                    $transactionStatus = "Belum dibayar";
                    break;
                case TransactionStatus::Pending->value:
                    $transactionStatus = "Pending";
                    break;
                case TransactionStatus::Success->value:
                    $transactionStatus = "Sukses";
                    break;
                case TransactionStatus::Failed->value:
                    $transactionStatus = "Gagal";
                    break;
            }

            $transacionType = null;
            switch ($transaction->type) {
                case TransactionType::Deposit->value:
                    $transacionType = "Deposit";
                    break;
                case TransactionType::Withdraw->value:
                    $transacionType = "Withdraw";
                    break;
            }

            $returnedData[] = [
                'order_id' => $transaction->order_id,
                'amount' => $transaction->amount,
                'type' => $transacionType,
                'status' => $transactionStatus,
                'created_at' => $transaction->created_at
            ];
        }

        return $returnedData;
    }
}
