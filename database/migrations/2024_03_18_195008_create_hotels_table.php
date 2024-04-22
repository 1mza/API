<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image'); // This column will store the image path
            $table->string('location');
            $table->text('description');
            $table->decimal('rate', 8, 2)->nullable(); // Rate can be decimal, representing the average rating
            $table->boolean('wifi')->default(false);
            $table->boolean('pool')->default(false);
            $table->boolean('car_parking')->default(false);
            $table->tinyInteger('sustainable_travel_level')->nullable(); // 1, 2, 3, etc.
            $table->enum('disability_accommodation', ['none', 'hearing', 'physical'])->default('none');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
