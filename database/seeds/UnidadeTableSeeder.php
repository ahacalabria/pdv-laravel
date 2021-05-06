<?php

use Illuminate\Database\Seeder;
use App\Unidade as Unidade;

class UnidadeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Unidade::create( [
        'id' => 1,
        'nome' => 'UNITARIO',
        'sigla' => 'UN'
        ] );
    }
}
