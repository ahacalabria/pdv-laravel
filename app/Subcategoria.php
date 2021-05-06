<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Subcategoria extends Model
{
    use AuditingTrait;

    protected $table = "subcategoria";
    protected $fillable = array('nome', 'categoria_id');
    public function categoria(){
      return $this->hasOne('App\Categoria','id','categoria_id');
    }
    public function produtos(){
      return $this->belongsToMany('App\Produto','produto_categoria');
    }
}
