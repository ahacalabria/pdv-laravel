<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pessoa_id')->unsigned();
            $table->string('name');
            $table->enum('level', ['root','administrador','gerente','caixa','vendedor']);
            $table->integer('limite_porcetagem');
            $table->decimal('limite_dinheiro',10,2);
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::table('users', function($table){
          $table->foreign('pessoa_id')->references('id')->on('pessoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
