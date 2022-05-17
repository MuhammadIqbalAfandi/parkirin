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
        Schema::create('transaction_outs', function (Blueprint $table) {
            $table->id();
            $table->string('plat_number');
            $table->unsignedInteger('price');
            $table->foreignId('transaction_in_id')->constrained();
            $table->foreignId('type_vehicle_id')->constrained();
            $table->foreignId('parking_fee_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('transaction_outs');
    }
};
