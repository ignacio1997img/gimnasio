<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaultsDetailsCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaults_details_cashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaults_detail_id')->nullable()->constrained('vaults_details');
            $table->decimal('cash_value', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaults_details_cashes');
    }
}
