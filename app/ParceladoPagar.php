<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class ParceladoPagar extends Model
{
    use AuditingTrait;
    protected $table = "parcelado_pagar";
    protected $fillable = array('numero', 'financeiro_pagar_id', 'valor', 'valor_pago', 'data_vencimento', 'data_pago', 'status');
    public function financeiro_pagar(){
      return $this->hasOne('App\FinanceiroPagar','id','financeiro_pagar_id');
    }
}
