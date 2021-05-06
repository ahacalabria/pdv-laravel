<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Categoria extends Model
{
    use AuditingTrait;
    protected $table = "categoria";
    protected $fillable = array('nome');

    public function subcategorias(){
        $this->hasMany('App\Subcategoria');
    }
    public function produtos(){
      return $this->belongsToMany('App\Produto','produto_categoria');
    }
}
