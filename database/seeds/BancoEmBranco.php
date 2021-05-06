<?php

use Illuminate\Database\Seeder;
use App\Banco as Banco;

class BancoEmBranco extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Banco::create( [
        'title' => 'SEM BANCO',
        ] );
    }
}
