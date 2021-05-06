<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaVendaProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('venda_produto', function (Blueprint $table) {
          $table->integer('venda_id')->unsigned();
          $table->integer('produto_id')->unsigned();
      });
      Schema::table('venda_produto', function($table){
        $table->foreign('venda_id')->references('id')->on('venda');
        $table->foreign('produto_id')->references('id')->on('produto');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('venda_produto');
    }
}
