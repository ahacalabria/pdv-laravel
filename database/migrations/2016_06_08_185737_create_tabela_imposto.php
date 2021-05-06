<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaImposto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('imposto', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nome');
          $table->decimal('valor',10,2);
          $table->enum('tipo', ['p', 'd']);
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
      Schema::drop('imposto');
    }
}
