<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call( 'TipoPagamentoTableSeeder' );
        $this->call( 'PessoaTableSeeder' );
        $this->call( 'UserTableSeeder' );
        $this->call( 'ImpostoTableSeeder' );
        $this->call( 'UnidadeTableSeeder' );
        $this->call( 'CategoriaTableSeeder' );
        $this->call( 'BancoEmBranco' );
    }
}
