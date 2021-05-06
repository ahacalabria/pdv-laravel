<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Estado extends Model
{
    use AuditingTrait;
    protected $table = 'estados';
    public function cidades(){
      return $this->hasMany('App\Cidade');
    }
}
