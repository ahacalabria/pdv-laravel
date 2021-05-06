<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableHistoricoBusca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('historico_busca', function (Blueprint $table) {
          $table->integer('produto_id')->unsigned();
          $table->integer('user_id')->unsigned();
      });
      Schema::table('historico_busca', function($table){
        $table->foreign('produto_id')->references('id')->on('produto');
        $table->foreign('user_id')->references('id')->on('users');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('historico_busca');
    }
}
