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

            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->foreignId('plan_id')->nullable()->constrained('plans');
            $table->foreignId('day_id')->nullable()->constrained('days');
            $table->foreignId('people_id')->nullable()->constrained('people');
            $table->smallInteger('hour')->nullable();

            $table->string('beforeImage')->nullable();
            $table->string('laterImage')->nullable();
            $table->decimal('beforeWeight', 5, 2)->nullable();
            $table->decimal('laterWeight', 5, 2)->nullable();
            

            $table->date('start')->nullable();
            $table->date('finish')->nullable();

            $table->decimal('subAmount',5,2)->nullable();
            $table->decimal('amount',8,2)->nullable();
            $table->smallInteger('credit')->nullable();
            

            $table->smallInteger('status')->default(1);
            $table->string('ip')->nullable();
            $table->string('mac')->nullable();
            $table->timestamps();
            $table->foreignId('userRegister_id')->nullable()->constrained('users');
            $table->foreignId('userDelete_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('clients');
    }
}
