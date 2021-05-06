<?php

use Illuminate\Database\Seeder;
use App\Pessoa as Pessoa;

class PessoaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pessoa::create( [
          'id' => 1,
          'tipo_cadastro' => 'funcionario',
          'tipo' => 'f',
          'nome' => 'Clever',
          'sobrenome' => 'IT Agência Web',
          'cpf' => '43706320134',
          'rg' => '2012027684320',
          'data_nascimento' => '2013-10-01',
          'estado_id' => 6,
          'cidade_id' => 796,
          'endereco' => 'Av. Leão Sampaio, 1990, 3° Andar, Sala 310',
          'bairro' => 'Lagoa Seca',
          'cep' => '63040-000',
          'telefone_1' => '(88) 99969-6987',
          'telefone_2' => '',
          'email' => 'contato@cleverit.com.br',
          'site' => 'www.cleverit.com.br',
          'banco_id' => '67'
          ] );
          Pessoa::create( [
            'id' => 2,
            'tipo_cadastro' => 'fornecedor',
            'tipo' => 'j',
            'razao_social' => 'REGIONAL',
            'nome_fantasia' => '',
            'cnpj' => '',
            'ie' => '',
            'data_nascimento' => '2013-10-01',
            'estado_id' => 6,
            'cidade_id' => 796,
            'endereco' => '',
            'bairro' => '',
            'cep' => '63000-000',
            'telefone_1' => '',
            'telefone_2' => '',
            'email' => '',
            'site' => '',
            'banco_id' => 67
            ] );
    }
}
