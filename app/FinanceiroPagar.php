<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class FinanceiroPagar extends Model
{
    use AuditingTrait;
    protected $table = "financeiro_pagar";
    protected $fillable = array('titulo', 'descricao', 'valor_total', 'quantidade_parcelas',
    'quantidade_parcelas_pagas', 'status');
}
