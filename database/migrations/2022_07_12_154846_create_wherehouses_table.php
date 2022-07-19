<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWherehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wherehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('busine_id')->nullable()->constrained('busines');
            $table->foreignId('provider_id')->nullable()->constrained('providers');
            $table->string('number')->nullable();

            $table->smallInteger('status')->default(1);

            $table->timestamps();
            $table->foreignId('userRegister_id')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('userDetele_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wherehouses');
    }
}
