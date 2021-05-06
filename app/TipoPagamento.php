<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class TipoPagamento extends Model
{
    use AuditingTrait;

    protected $table = "tipo_pagamento";
    protected $fillable = array('tipo');
}
