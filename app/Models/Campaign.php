<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    // Defina a tabela associada ao modelo
    protected $table = 'campaigns';

    // Defina os campos que são atribuíveis em massa (mass assignment)
    protected $fillable = [
        'name',
        'domain_id',
        'language',
        'traffic_source',
        'safe_page',
        'method_safe',
        'method_offer',
        'offer_pages',
        'target_countries',
        'target_devices',
        'hash',
        'xid',
    ];

    // Defina os campos que serão convertidos para o tipo de dado adequado (JSON, por exemplo)
    protected $casts = [
        'offer_pages' => 'array',
        'target_countries' => 'array',
        'target_devices' => 'array',
    ];

    // Defina os relacionamentos, caso necessário. Aqui, assumimos que existe uma tabela 'domains'
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestLog::class);
    }
}
