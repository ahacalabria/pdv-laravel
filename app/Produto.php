<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class Produto extends Model
{

    use AuditingTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = "produto";
    protected $fillable = array('unidade_id','pessoa_id','codigo',
    'codigo_ncm', 'titulo','descricao','custo','preco','quantidade_estoque','frete','valor_agregado', 'desabilitar');

    public function unidade(){
      return $this->hasOne('App\Unidade', 'id', 'unidade_id');
    }

    public function pessoa(){
      return $this->hasOne('App\Pessoa', 'id', 'pessoa_id');
    }

    public function impostos(){
      return $this->belongsToMany('App\Imposto','produto_imposto');
    }

    public function movimentacoes(){
      return $this->belongsToMany('App\Movimentacao','produto_movimentacao');
    }

    public function vendas(){
      return $this->belongsToMany('App\Venda','venda_produto');
    }

    public function notas(){
      return $this->belongsToMany('App\Nota','nota_produto');
    }

    public function subcategorias(){
      return $this->belongsToMany('App\Subcategoria','produto_categoria');
    }
    public function categorias(){
      return $this->belongsToMany('App\Categoria','produto_grupo');
    }
    public function historico_busca(){
      return $this->belongsToMany('App\Users', 'historico_busca')->latest();
    }
}
