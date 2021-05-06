<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceiroReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('financeiro_receber', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('venda_id')->unsigned();
           $table->integer('pessoa_recebeu_id')->unsigned();
           $table->decimal('valor_total',10,2);
           $table->integer('quantidade_parcelas');
           $table->integer('quantidade_parcelas_pagas');
           $table->date('data_vencimento');
           $table->enum('status', ['pendente','pago']);
           $table->timestamps();
       });
       Schema::table('financeiro_receber', function($table){
           $table->foreign('venda_id')->references('id')->on('venda');
           $table->foreign('pessoa_recebeu_id')->references('id')->on('pessoa');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('financeiro_receber');
     }
}
