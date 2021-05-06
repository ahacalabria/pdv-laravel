<?php

use Illuminate\Database\Seeder;
use App\TipoPagamento as TipoPagamento;

class TipoPagamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TipoPagamento::truncate();
        TipoPagamento::create( [
          'id' => 1,
          'tipo' => 'À VISTA'
          ] );
          TipoPagamento::create( [
            'id' => 2,
            'tipo' => 'CARTÃO'
            ] );
            TipoPagamento::create( [
              'id' => 3,
              'tipo' => 'CHEQUE'
              ] );
              TipoPagamento::create( [
                'id' => 4,
                'tipo' => 'PARCELADO'
                ] );
                TipoPagamento::create( [
                  'id' => 5,
                  'tipo' => 'CHEQUE PARCELADO'
                  ] );
    }
}
