<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('pessoa', function (Blueprint $table) {
          $table->increments('id');

          $table->enum('tipo_cadastro', ['cliente', 'fornecedor', 'funcionario']);
          $table->enum('tipo', ['f', 'j']);

          $table->string('nome');
          $table->string('sobrenome');
          $table->string('nome_fantasia');
          $table->string('razao_social');
          $table->string('cpf');
          $table->string('cnpj');
          $table->string('rg');
          $table->string('ie');
          $table->date('data_nascimento');

          $table->integer('cidade_id')->unsigned();
          $table->integer('estado_id')->unsigned();

          $table->string('endereco');
          $table->string('bairro');
          $table->string('cep');
          $table->string('telefone_1');
          $table->string('telefone_2');
          $table->string('email');
          $table->string('site');

          $table->integer('banco_id')->unsigned();
          $table->string('agencia');
          $table->string('conta');
          $table->mediumText('observacao');

          $table->string('nome_responsavel');
          $table->string('telefone_responsavel');

          $table->boolean('status')->default(true);
          $table->timestamps();
      });
      Schema::table('pessoa', function($table) {
          $table->foreign('cidade_id')->references('id')->on('cidades');
          $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::drop('pessoa');
    }
}
