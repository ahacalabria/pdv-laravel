<?php

use Illuminate\Database\Seeder;
use App\Categoria as Categoria;
use App\Subcategoria as Subcategoria;

class CategoriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Categoria::create( [
        'id' => 1,
        'nome' => 'REGIONAL'
        ] );
        Subcategoria::create( [
          'id' => 1,
          'nome' => 'DISVERSOS',
          'categoria_id' => 1
          ] );
    }
}
