<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtmsRequest extends Model
{
    protected $table = 'utms_request';

    protected $fillable = [
        'request_id', 'cwr', 'twr', 'gwr', 'domain', 'cr', 'plc', 'mtx',
        'rdn', 'kw', 'cpc', 'disp', 'int', 'loc', 'net', 'pos', 'dev',
        'gclid', 'wbraid', 'gbraid', 'ref_id', 'xid'
    ];

    // Relacionamento com a tabela requests
    public function request() {
        return $this->belongsTo(Request::class);
    }
}
