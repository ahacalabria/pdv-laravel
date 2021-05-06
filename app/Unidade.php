<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Unidade extends Model
{
    use AuditingTrait;

    protected $table = "unidade";
    protected $fillable = array('nome', 'sigla');
}
