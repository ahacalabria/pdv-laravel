<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class ParceladoReceber extends Model
{
    use AuditingTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "parcelado_receber";
    protected $fillable = array('numero', 'financeiro_receber_id', 'valor', 'valor_pago', 'data_vencimento', 'data_pago', 'status', 'valor_troco', 'obs');
    public function financeiro_receber(){
      return $this->hasOne('App\FinanceiroReceber','id','financeiro_receber_id');
    }
}
