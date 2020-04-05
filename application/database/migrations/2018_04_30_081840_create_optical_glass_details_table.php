<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpticalGlassDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optical_glass_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('property')->nullable();
            $table->string('refractive_index')->nullable();
            $table->string('anti_reflex_color')->nullable();
            $table->string('block')->nullable();
            $table->string('bloc_troll')->nullable();
            $table->string('photocrophy')->nullable();
            $table->string('photo_color')->nullable();
            $table->string('polycarbonate')->nullable();
            $table->string('poly_break')->nullable();
            $table->string('color_white')->nullable();
            $table->string('colored_score')->nullable();
            $table->string('watering')->nullable();
            $table->string('structure')->nullable();
            $table->string('yu_vie')->nullable();
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
        Schema::dropIfExists('optical_glass_details');
    }
}
