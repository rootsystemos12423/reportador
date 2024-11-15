<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushcutWebhook extends Model
{
    protected $fillable = [
        'name',
        'url',
    ];
}
