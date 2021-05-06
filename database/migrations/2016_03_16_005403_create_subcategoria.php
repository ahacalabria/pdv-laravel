<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubcategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('subcategoria', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nome');
          $table->integer('categoria_id')->unsigned();
          $table->timestamps();
      });
      Schema::table('subcategoria', function($table) {
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
        Schema::drop('subcategoria');
    }
}
