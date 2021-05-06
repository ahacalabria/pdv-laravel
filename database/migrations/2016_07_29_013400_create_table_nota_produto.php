<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotaProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('nota_produto', function (Blueprint $table) {
          $table->integer('nota_id')->unsigned();
          $table->integer('produto_id')->unsigned();
      });
      Schema::table('nota_produto', function($table){
        $table->foreign('nota_id')->references('id')->on('nota');
        $table->foreign('produto_id')->references('id')->on('produto');
      });
      Schema::table('nota', function($table){
        $table->dropForeign('nota_fornecedor_id_foreign');
        $table->dropColumn('fornecedor_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nota_produto');
    }
}
