<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('car_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->date('arrival_date');
            $table->date('return_date');
            $table->boolean('need_driver')->default(false);
            $table->boolean('physical_disability_accessible')->default(false);
            // Add any other relevant fields
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_reservations');
    }
}
