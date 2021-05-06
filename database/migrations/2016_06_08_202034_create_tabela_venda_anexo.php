<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaVendaAnexo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('venda_anexo', function (Blueprint $table) {
          $table->integer('venda_id')->unsigned();
          $table->integer('anexo_id')->unsigned();
      });
      Schema::table('venda_anexo', function($table){
        $table->foreign('venda_id')->references('id')->on('venda');
        $table->foreign('anexo_id')->references('id')->on('anexo');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('venda_anexo');
    }
}
