<?php

namespace App\Jobs;

use App\Models\BalanceHistory;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Params\ProcessBalanceParam;
use Illuminate\Support\Facades\DB;
use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\Type as TransactionType;
use Throwable;

class ProcessBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProcessBalanceParam $param;

    /**
     * Create a new job instance.
     */
    public function __construct(ProcessBalanceParam $param)
    {
        $this->param = $param;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $maxAttempt = 3;
        $attemptRemaining = $maxAttempt;
        while ($attemptRemaining > 0) {
            DB::beginTransaction();
            try {
                DB::statement("SET TRANSACTION ISOLATION LEVEL REPEATABLE READ");
                $user = User::where('id', $this->param->userUid)->first(['current_balance']);

                $amount = (float)$this->param->amount;

                if ($this->param->transactionType === TransactionType::Withdraw) {
                    $amount *= -1;
                }

                User::where('id', $this->param->userUid)->update([
                    'current_balance' => $user->current_balance + $amount
                ]);

                Transaction::where('order_id', $this->param->transactionOrderId)->update([
                    'status' => TransactionStatus::Success
                ]);
                DB::commit();

                return;
            } catch (Throwable $t) {
                DB::rollBack();

                $attemptRemaining--;
                if ($attemptRemaining == 0) {
                    Transaction::where('order_id', $this->param->transactionOrderId)->update([
                        'status' => TransactionStatus::Success
                    ]);

                    $this->fail($t);
                }
            }

            sleep(($maxAttempt - $attemptRemaining) * 2);
        }
    }
}
