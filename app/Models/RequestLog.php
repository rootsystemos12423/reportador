<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'ip',
        'continent',
        'country',
        'country_code',
        'timezone',
        'isp',
        'org',
        'asn',
        'reverse_dns',
        'language',
        'device',
        'user_agent',
        'allowed',
        'reason',
        'campaign_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function utms() {
        return $this->hasMany(UtmsRequest::class, 'request_id', 'id');
    }
}
