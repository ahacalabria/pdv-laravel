<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PessoaFornecedorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('nota', function($table) {
        //     $table->integer('pessoa_id')->unsigned();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('nota', function($table) {
        //     $table->foreign('pessoa_id')->references('id')->on('pessoa');
        // });
    }
}
