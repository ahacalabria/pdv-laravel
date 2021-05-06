<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutoCategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('produto_categoria', function (Blueprint $table) {
           $table->integer('produto_id')->unsigned();
           $table->integer('subcategoria_id')->unsigned();
       });
       Schema::table('produto_categoria', function($table) {
           $table->foreign('produto_id')->references('id')->on('produto');
           $table->foreign('subcategoria_id')->references('id')->on('subcategoria');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('produto_categoria');
     }
}
