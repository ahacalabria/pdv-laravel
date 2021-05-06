<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGrupo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('produto_grupo', function (Blueprint $table) {
          $table->integer('produto_id')->unsigned();
          $table->integer('categoria_id')->unsigned();
      });
      Schema::table('produto_grupo', function($table) {
          $table->foreign('produto_id')->references('id')->on('produto');
          $table->foreign('categoria_id')->references('id')->on('categoria');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('produto_grupo');
    }
}
