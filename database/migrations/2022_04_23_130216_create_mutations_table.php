<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [1, 2]); // 1(income) 2(expense)
            $table->unsignedInteger('amount');
            $table->foreignId('out_transaction_id')->nullable()->default(null)->constrained();
            $table->foreignId('expense_id')->nullable()->default(null)->constrained();
            $table->foreignId('top_up_id')->nullable()->default(null)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutations');
    }
};
