<?php

use Illuminate\Database\Seeder;
use App\Imposto as Imposto;

class ImpostoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Imposto::create( [
        'id' => 1,
        'nome' => 'ISENTO',
        'valor' => 0
        ] );
    }
}
