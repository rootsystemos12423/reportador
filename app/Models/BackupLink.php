<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupLink extends Model
{
    protected $fillable = [
        'landing_page_id',
        'url',
        'description',
    ];

    /**
     * Relacionamento: um link de backup pertence a uma landing page.
     */
    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class);
    }
}
