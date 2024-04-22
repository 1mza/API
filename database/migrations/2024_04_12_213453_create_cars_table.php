<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('registration_number');
            $table->text('description')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->boolean('air_conditioning')->nullable();
            $table->string('transmission')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('fuel_fill_up')->nullable();
            $table->decimal('price_per_km', 10, 2)->nullable();
            $table->integer('min_rental_days')->nullable();
            $table->decimal('collision_damage_waiver', 10, 2)->nullable();
            $table->boolean('theft_protection')->nullable();
            $table->boolean('physical_disability_accessible')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
