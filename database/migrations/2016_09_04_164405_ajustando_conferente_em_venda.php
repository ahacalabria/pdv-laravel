<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AjustandoConferenteEmVenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Schema::table('nota', function ($table) {
        // $table->dropColumn('pessoa_id');
      // });
      // Schema::table('venda', function($table) {
          // $table->integer('pessoa_conferente_id')->unsigned();
      // });
      // Schema::table('venda', function($table) {
          // $table->foreign('pessoa_conferente_id')->references('id')->on('pessoa');
      // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('venda');
    }
}
