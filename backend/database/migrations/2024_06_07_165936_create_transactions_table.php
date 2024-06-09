<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('order_id')->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id');
            $table->decimal('amount', 10, 2);
            $table->integer('status'); // 1. prepare | 2. pending | 3. success | 4. failed
            $table->integer('type'); // 1 deposit. 2 withdraw
            $table->timestampsTz();

            $table->index('order_id');
            $table->index('user_id');
            $table->index(['user_id', 'status']);
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
