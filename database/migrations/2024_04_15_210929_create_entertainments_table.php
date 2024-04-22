<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertainmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entertainments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['مأكولات بحريه', 'مشويات و كشري', 'سوبرماركت']);
            $table->string('location');
            $table->text('description');
            $table->decimal('rate', 8, 2)->nullable(); // Rate can be decimal, representing the average rating
            $table->boolean('physical_disability_accessible');
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
        Schema::dropIfExists('entertainments');
    }
}
