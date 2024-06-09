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
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid')->default(DB::raw('gen_random_uuid()'));
            $table->uuid('transaction_order_id');
            $table->uuid('user_id');
            $table->decimal('amount', 10, 2);
            $table->timestampsTz();

            $table->primary('id');
            $table->index('uid');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_histories');
    }
};
