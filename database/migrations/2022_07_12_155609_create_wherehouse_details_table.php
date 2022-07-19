<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWherehouseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wherehouse_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wherehouse_id')->nullable()->constrained('wherehouses');
            $table->foreignId('article_id')->nullable()->constrained('articles');

            $table->decimal('amount',5,2)->nullable();
            $table->decimal('items',5,2)->nullable();
            $table->decimal('item',5,2)->nullable();
            $table->decimal('unitPrice',5,2)->nullable();
            $table->decimal('itemEarnings',5,2)->nullable();

            $table->date('expiration')->nullable();

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
        Schema::dropIfExists('wherehouse_details');
    }
}
