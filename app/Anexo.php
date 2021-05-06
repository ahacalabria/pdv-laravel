<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Anexo extends Model
{
    use AuditingTrait;
    protected $table = "anexo";
    protected $fillable = array('nome', 'caminho', 'tipo_pessoa', 'historico',
                            'data_emissao', 'banco_id', 'agencia', 'conta_corrente',
                          'numero_cheque', 'valor', 'data_vencimento', 'cpfcnpj');

    public function vendas(){
        return $this->belongsToMany('App\Venda','venda_anexo');
    }
}
