<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyIndex extends Model
{
    protected $table = 'shopify_index';

    protected $fillable = [
        'backup_link_id',
        'index_file_path',
    ];

    public function backupLink()
    {
        return $this->belongsTo(BackupLink::class, 'backup_link_id');
    }
}
