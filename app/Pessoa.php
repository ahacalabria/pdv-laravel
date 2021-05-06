<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Estado;
use App\Cidade;
use App\Banco;

use OwenIt\Auditing\AuditingTrait;

class Pessoa extends Model
{
  use AuditingTrait;
  use SoftDeletes;
  protected $table = 'pessoa';
  protected $dates = ['deleted_at'];
  protected $fillable = array('tipo_cadastro','tipo', 'nome', 'sobrenome','nome_fantasia', 'razao_social',
  'cpf', 'cnpj', 'rg', 'ie', 'data_nascimento', 'estado_id', 'cidade_id', 'endereco', 'bairro',
  'cep', 'telefone_1', 'telefone_2', 'email', 'site', 'nome_responsavel', 'telefone_responsavel',
  'banco_id', 'agencia', 'conta', 'observacao', 'sexo', 'cargo');

  public function cidade(){
    return $this->hasOne('App\Cidade','id','cidade_id');
  }

  public function estado(){
    return $this->hasOne('App\Estado','id','estado_id');
  }

  public function banco(){
    return $this->hasOne('App\Banco','id','banco_id');
  }
}
