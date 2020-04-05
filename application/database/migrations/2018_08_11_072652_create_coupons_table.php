<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_key')->unique();
            $table->string('discount_type');
            $table->bigInteger('coupon_amount');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->bigInteger('minimum_amount')->default(0);
            $table->bigInteger('maximum_amount')->default(0);
            $table->integer('exclude_sale')->nullable();
            $table->integer('disposable')->nullable();
            $table->text('product_categories')->nullable();
            $table->text('product_ids')->nullable();
            $table->text('exclude_product_ids')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
