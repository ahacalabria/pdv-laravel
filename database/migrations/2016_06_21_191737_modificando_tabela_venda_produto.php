<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModificandoTabelaVendaProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('venda_produto', function($table) {
        $table->double("quantidade",18,3);
        $table->bigInteger('codigo');
        $table->bigInteger('codigo_ncm');
        $table->string('titulo');
        $table->string('descricao');
        $table->decimal('custo',10,2);
        $table->decimal('preco',10,2);
        $table->string('unidade_nome');
        $table->decimal('subtotal',10,2);
        if ((DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')) {
            $table->json('impostos_info');
        } else {
            $table->text('impostos_info');
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('venda_produto');
    }
}
