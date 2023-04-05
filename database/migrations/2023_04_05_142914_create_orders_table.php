<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('table');

            $table->unsignedBigInteger('smena_id')->nullable();
            $table->foreign('smena_id')->references('id')->on('smenas');

            $table->unsignedBigInteger('shift_worker')->nullable();
            $table->foreign('shift_worker')->references('id')->on('users');
            $table->string('status')->default('Принят');
            $table->integer('price')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
