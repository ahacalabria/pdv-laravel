<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Nota extends Model
{
  use AuditingTrait;

  protected $table = "nota";
  protected $fillable = array('codigo','data_emissao',
  'data_entrada', 'valor_total','valor_frete');

  public function produtos(){
    return $this->belongsToMany('App\Produto', 'nota_produto');
  }
}
