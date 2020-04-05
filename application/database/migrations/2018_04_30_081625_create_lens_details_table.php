<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLensDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lens_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('curvature')->nullable();
            $table->string('structure')->nullable();
            $table->string('consumption_period')->nullable();
            $table->string('color')->nullable();
            $table->string('color_description')->nullable();
            $table->string('number')->nullable();
            $table->string('astigmatism')->nullable();
            $table->string('thickness')->nullable();
            $table->string('abatement')->nullable();
            $table->string('oxygen_supply')->nullable();
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
        Schema::dropIfExists('lens_details');
    }
}
