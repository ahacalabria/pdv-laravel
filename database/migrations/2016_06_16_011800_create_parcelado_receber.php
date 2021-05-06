<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParceladoReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('parcelado_receber', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('financeiro_receber_id')->unsigned();
           $table->decimal('valor',10,2);
           $table->decimal('valor_pago',10,2);
           $table->date('data_vencimento');
           $table->date('data_pago');
           $table->enum('status', ['pendente','vencida','pago']);
           $table->timestamps();
       });
       Schema::table('parcelado_receber', function($table){
           $table->foreign('financeiro_receber_id')->references('id')->on('financeiro_receber');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('parcelado_receber');
     }
}
