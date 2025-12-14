<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_special_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('special_price', 10, 2);
            $table->timestamps();

            $table->foreign('client_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            // ضمان عدم تكرار السعر الخاص لنفس المنتج والعميل
            $table->unique(['client_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_special_prices');
    }
};
