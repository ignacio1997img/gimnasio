<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('busine_id')->nullable()->constrained('busines'); //para saber de que gimnasio es
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers'); //para saber de que caja es 
            $table->string('type')->nullable();//para saber si es un plan o es venta de producto

            $table->foreignId('plan_id')->nullable()->constrained('plans');//para 

            $table->foreignId('people_id')->nullable()->constrained('people');

            $table->smallInteger('day')->nullable();//para saber cuantos dias es el servicio
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
        
            $table->decimal('subAmount',10,2)->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->smallInteger('credit')->nullable();            

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
        Schema::dropIfExists('clients');
    }
}
