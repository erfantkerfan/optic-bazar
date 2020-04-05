<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile', 14)->unique();
            $table->string('conferm', 80)->nullable();
            $table->bigInteger('credit')->default(0);
            $table->string('status', 80)->default('not_verified');
            $table->string('role', 80)->default('user');
            $table->string('phone', 14)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('area', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('passage')->nullable();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamp('conferm_time')->nullable();
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
        Schema::dropIfExists('users');
    }
}
