<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Banco extends Model
{
    use AuditingTrait;
    protected $table = "bank";
    protected $fillable = array('title');
}
