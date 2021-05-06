<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('produto', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('unidade_id')->unsigned();
          $table->integer('pessoa_id')->unsigned();
          $table->bigInteger('codigo')->unique();
          $table->bigInteger('codigo_ncm')->unique();
          $table->string('titulo');
          $table->string('descricao');
          $table->decimal('custo',10,2);
          $table->decimal('preco',10,2);
          $table->bigInteger('quantidade_estoque');
          $table->timestamps();
      });
      Schema::table('produto', function($table){
          $table->foreign('unidade_id')->references('id')->on('unidade');
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
        Schema::drop('produto');
    }
}
