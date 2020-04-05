<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('type')->default('import');
            $table->string('name');
            $table->string('birth');
            $table->string('type_product')->nullable();

            $table->integer('dodid')->nullable();
            $table->string('rcount')->nullable();
            $table->string('lcount')->nullable();
            $table->string('rsph')->nullable();
            $table->string('lsph')->nullable();
            $table->string('rcyl')->nullable();
            $table->string('lcyl')->nullable();
            $table->string('raxis')->nullable();
            $table->string('laxis')->nullable();
            $table->string('radd')->nullable();
            $table->string('ladd')->nullable();

            $table->string('dodid_rdia')->nullable();
            $table->string('dodid_ldia')->nullable();
            $table->string('dodid_ripd')->nullable();
            $table->string('dodid_lipd')->nullable();
            $table->string('dodid_rprism')->nullable();
            $table->string('dodid_lprism')->nullable();
            $table->string('dodid_rprism_base')->nullable();
            $table->string('dodid_lprism_base')->nullable();
            $table->string('dodid_rcorridor')->nullable();
            $table->string('dodid_lcorridor')->nullable();
            $table->string('dodid_rdec')->nullable();
            $table->string('dodid_ldec')->nullable();


            $table->integer('prisma')->nullable();
            $table->string('prisma_rprisma1')->nullable();
            $table->string('prisma_lprisma1')->nullable();
            $table->string('prisma_rdegrees1')->nullable();
            $table->string('prisma_ldegrees1')->nullable();
            $table->string('prisma_rprisma2')->nullable();
            $table->string('prisma_lprisma2')->nullable();
            $table->string('prisma_rdegrees2')->nullable();
            $table->string('prisma_ldegrees2')->nullable();

            $table->text('image')->nullable();
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
        Schema::dropIfExists('prescriptions');
    }
}
