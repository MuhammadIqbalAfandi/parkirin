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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('login_time');
            $table->dateTime('time_out');
            $table->unsignedInteger('price');
            $table->string('plat_number')->unique();
            $table->string('input_by');
            $table->string('capture_vehicle');
            $table->foreignId('type_vehicle_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('member_id')->nullable()->default(null)->constrained();
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
        Schema::dropIfExists('tansactions');
    }
};
