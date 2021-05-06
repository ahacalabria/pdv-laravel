<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Movimentacao extends Model
{
  use AuditingTrait;
  protected $table = "movimentacao";
  protected $fillable = array('numero_nota', 'emitente_destinatario', 'valor_unitario','valor_total', 'quantidade', 'estoque');

  public function produtos(){
    return $this->belongsToMany('App\Produtos','produto_movimentacao');
  }
}
