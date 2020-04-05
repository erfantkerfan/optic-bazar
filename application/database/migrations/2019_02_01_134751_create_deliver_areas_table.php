<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliver_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_id');
            $table->string('type');
            $table->integer('area');
            $table->integer('without_time')->nullable();
            $table->integer('without_difference_time')->nullable();
            $table->timestamp('without_date')->nullable();
            $table->integer('without_price')->nullable();
            $table->integer('with_time')->nullable();
            $table->integer('with_difference_time')->nullable();
            $table->timestamp('with_date')->nullable();
            $table->integer('with_price')->nullable();
            $table->integer('receipt_time')->nullable();
            $table->integer('receipt_difference_time')->nullable();
            $table->timestamp('receipt_date')->nullable();
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
        Schema::dropIfExists('deliver_areas');
    }
}
