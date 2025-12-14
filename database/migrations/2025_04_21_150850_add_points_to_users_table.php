<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->double('saudi_points')->default(0);
            $table->double('malaysian_points')->default(0);

        });
        Schema::table('carts', function (Blueprint $table) {
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');

        });
        Schema::create('points_logs', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('client_id')->unsigned()->nullable();

            $table->double('amount')->unsigned();
            $table->enum('type', ['self', 'client'])->default('self');
            $table->enum('points_type', ['malaysian_points', 'saudi_points'])->default('saudi_points');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
