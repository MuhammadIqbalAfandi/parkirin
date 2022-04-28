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
        Schema::create('max_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('max');
            $table->foreignId('type_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_vehicle_id')->constrained();
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
        Schema::dropIfExists('max_vehicles');
    }
};
