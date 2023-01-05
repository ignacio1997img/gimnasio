<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services');            
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('day')->nullable();
            $table->decimal('amount',10, 2)->nullable();
            $table->smallInteger('status')->default(1);          
            $table->timestamps();
            $table->foreignId('userRegister_id')->nullable()->constrained('users');


            $table->softDeletes();
            $table->foreignId('userDelete_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
