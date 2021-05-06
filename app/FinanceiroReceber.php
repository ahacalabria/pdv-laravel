<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class FinanceiroReceber extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    use AuditingTrait;
    protected $table = "financeiro_receber";
    protected $fillable = array('venda_id', 'pessoa_recebeu_id', 'valor_total', 'quantidade_parcelas',
    'quantidade_parcelas_pagas', 'status');

    public function venda(){
      return $this->hasOne('App\Venda','id','venda_id');
    }
    public function recebedor(){
      return $this->hasOne('App\Pessoa', 'id', 'pessoa_recebeu_id');
    }
}
