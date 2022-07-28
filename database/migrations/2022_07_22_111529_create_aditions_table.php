<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->decimal('cant', 5,2)->nullable();
            $table->text('observation')->nullable();
            $table->string('type')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->foreignId('userRegister_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('aditions');
    }
}
