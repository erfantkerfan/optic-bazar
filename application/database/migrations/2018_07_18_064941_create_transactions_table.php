<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('price')->default(0);
            $table->bigInteger('discount')->default(0);
            $table->bigInteger('orginal_price')->default(0);
            $table->string('type')->default('order');
            $table->string('key')->unique();
            $table->string('payment_method')->nullable();
            $table->string('tracking_code')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->string('posting_status')->default('pending');
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
        Schema::dropIfExists('transactions');
    }
}
