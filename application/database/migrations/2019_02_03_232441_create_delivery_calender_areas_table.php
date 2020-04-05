<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryCalenderAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_calender_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_id');
            $table->string('type');
            $table->integer('area');
            $table->integer('start')->nullable();
            $table->integer('end')->nullable();
            $table->string('price')->nullable();
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('delivery_calender_areas');
    }
}
