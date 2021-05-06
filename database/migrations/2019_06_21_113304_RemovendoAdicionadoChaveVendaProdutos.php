<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovendoAdicionadoChaveVendaProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('venda_produto', function (Blueprint $table) {   
        //     $table->dropPrimary();
        // });
        
        Schema::table('venda_produto', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
