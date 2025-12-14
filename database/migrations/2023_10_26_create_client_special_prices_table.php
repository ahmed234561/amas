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
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('special_price', 10, 2);
            $table->timestamps();

            // منع تكرار نفس المنتج لنفس العميل
            $table->unique(['client_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_special_prices');
    }
};
