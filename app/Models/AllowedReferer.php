<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedReferer extends Model
{
    protected $table = 'allowed_referers';

    protected $fillable = [
        'referer',
        'campaign_type',
    ];
}
