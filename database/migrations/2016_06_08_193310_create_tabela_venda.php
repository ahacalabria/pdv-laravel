<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaVenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('venda', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('tipo_pagamento_id')->unsigned();
          $table->integer('pessoa_cliente_id')->unsigned();
          $table->integer('pessoa_vendedor_id')->unsigned();
          $table->enum('status', ['aberta','fechada','cancelada']);
          $table->decimal('valor_total',10,2);
          $table->enum('tipo_desconto', ['p','d']);
          $table->decimal('valor_desconto',10,2);
          $table->decimal('valor_frete',10,2);
          $table->decimal('valor_liquido',10,2);
          $table->timestamps();
      });
      Schema::table('venda', function($table){
          $table->foreign('tipo_pagamento_id')->references('id')->on('tipo_pagamento');
          $table->foreign('pessoa_cliente_id')->references('id')->on('pessoa');
          $table->foreign('pessoa_vendedor_id')->references('id')->on('pessoa');
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
