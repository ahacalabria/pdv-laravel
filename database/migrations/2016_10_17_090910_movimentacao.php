<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Movimentacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('movimentacao', function($table) {
        $table->increments('id');
        $table->string("numero_nota");
        $table->string('emitente_destinatario');
        $table->decimal('valor_unitario',10,2);
        $table->decimal('valor_total',10,2);
        $table->bigInteger('quantidade');
        $table->bigInteger('estoque');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimentacao');
    }
}
