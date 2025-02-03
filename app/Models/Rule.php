<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    protected $fillable = [
        'action',             // "block" ou "allow"
        'condition_type',     // "ip", "asn", "isp", "user-agent"
        'condition_operator', // "equal", "contains", "different"
        'values',             // JSON com valores armazenados
    ];

    protected $casts = [
        'values' => 'array', // Transforma em array automaticamente
    ];
}
