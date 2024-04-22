<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->date('arrive_date');
            $table->date('leave_date');
            $table->integer('num_of_adults');
            $table->integer('num_of_children');
            // Add any other relevant fields
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_reservations');
    }
}
