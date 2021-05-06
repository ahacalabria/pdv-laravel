<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModificandoTabelaAnexo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('anexo', function($table) {
        $table->enum("tipo_pessoa",['f','j']);
        $table->string('historico');
        $table->date('data_emissao');
        $table->integer('banco_id')->unsigned();
        $table->string('agencia');
        $table->string('conta_corrente');
        $table->string('numero_cheque');
        $table->decimal('valor',10,2);
        $table->date('data_vencimento');
        $table->string('cpfcnpj');
      });
      Schema::table('anexo', function($table){
          $table->foreign('banco_id')->references('id')->on('bank');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('anexo');
    }
}
