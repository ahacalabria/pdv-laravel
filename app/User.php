<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class User extends Authenticatable
{
    use AuditingTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pessoa_id', 'level', 'limite_porcetagem', 'limite_dinheiro'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function funcionario(){
      return $this->hasOne('App\Pessoa', 'id' ,'pessoa_id');
    }

    public function historico_busca(){
        return $this->belongsToMany('App\Produto', 'historico_busca')->latest();
    }

}
