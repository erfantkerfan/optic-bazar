<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('prescription_id');
            $table->integer('product_id')->default(0);
            $table->integer('detail_id')->default(0);
            $table->bigInteger('price')->default(0);
            $table->integer('lathe_id')->default(0);
            $table->text('lathe')->nullable();
            $table->string('type_shipping')->nullable();
            $table->timestamp('get_box_date')->nullable();
            $table->string('get_box_time')->nullable();
            $table->timestamp('send_box_date')->nullable();
            $table->string('send_box_time')->nullable();
            $table->string('order_key')->unique();
            $table->string('status')->default('not_completed');
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
        Schema::dropIfExists('orders');
    }
}
