<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Imposto extends Model
{
    use AuditingTrait;
    protected $table = "imposto";
    protected $fillable = array('nome', 'valor', 'tipo');

    public function produtos(){
      return $this->belongsToMany('App\Produto','produto_imposto');
    }
}
