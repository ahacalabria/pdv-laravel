<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Venda extends Model
{

    use AuditingTrait;

    protected $table = "venda";
    protected $fillable = array('tipo_pagamento_id', 'pessoa_cliente_id', 'pessoa_vendedor_id', 'com_nota',
                    'status', 'valor_total', 'tipo_desconto', 'valor_desconto', 'valor_frete', 'valor_liquido','data_venda','pessoa_conferente_id');

                    public function tipo_pagamento(){
                      return $this->hasOne('App\TipoPagamento', 'id', 'tipo_pagamento_id');
                    }
                    public function cliente(){
                      return $this->hasOne('App\Pessoa', 'id' ,'pessoa_cliente_id');
                    }
                    public function vendedor(){
                      return $this->hasOne('App\Pessoa','id', 'pessoa_vendedor_id');
                    }
                    public function conferente(){
                      return $this->hasOne('App\Pessoa','id', 'pessoa_conferente_id');
                    }
                    public function produtos(){
                      return $this->belongsToMany('App\Produto', 'venda_produto')
                        ->withPivot('id','quantidade','codigo','codigo_ncm','titulo','descricao','custo','preco','unidade_nome','subtotal','impostos_info');
                    }
                    public function anexos(){
                      return $this->belongsToMany('App\Anexo', 'venda_anexo');
                    }
}
