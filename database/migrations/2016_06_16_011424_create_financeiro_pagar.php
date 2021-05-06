<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceiroPagar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('financeiro_pagar', function (Blueprint $table) {
           $table->increments('id');
           $table->string('titulo');
           $table->text('descricao');
           $table->decimal('valor_total',10,2);
           $table->integer('quantidade_parcelas');
           $table->integer('quantidade_parcelas_pagas');
           $table->enum('status', ['pendente','pago']);
           $table->timestamps();
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('financeiro_pagar');
     }
 }
