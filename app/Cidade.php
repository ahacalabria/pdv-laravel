<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Cidade extends Model
{
    use AuditingTrait;
    protected $table = 'cidades';

}
