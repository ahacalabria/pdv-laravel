<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaImpostoProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('produto_imposto', function (Blueprint $table) {
          $table->integer('produto_id')->unsigned();
          $table->integer('imposto_id')->unsigned();
      });
      Schema::table('produto_imposto', function($table){
          $table->foreign('produto_id')->references('id')->on('produto');
          $table->foreign('imposto_id')->references('id')->on('imposto');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('produto_imposto');
    }
}
