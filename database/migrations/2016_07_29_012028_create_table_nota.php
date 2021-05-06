<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('nota', function (Blueprint $table) {
          $table->increments('id');
          $table->text('codigo');
          $table->integer('fornecedor_id')->unsigned();
          $table->dateTime('data_emissao');
          $table->dateTime('data_entrada');
          $table->decimal('valor_total',10,2);
          $table->decimal('valor_frete',10,2);
          $table->timestamps();
      });
      Schema::table('nota', function($table){
          $table->foreign('fornecedor_id')->references('id')->on('pessoa');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nota');
    }
}
