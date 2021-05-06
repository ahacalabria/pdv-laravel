<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProdutoMovimentacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('produto_movimentacao', function (Blueprint $table) {
          $table->integer('produto_id')->unsigned();
          $table->integer('movimentacao_id')->unsigned();
      });
      Schema::table('produto_movimentacao', function($table){
        $table->foreign('produto_id')->references('id')->on('produto');
        $table->foreign('movimentacao_id')->references('id')->on('movimentacao');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('produto_movimentacao');
    }
}
