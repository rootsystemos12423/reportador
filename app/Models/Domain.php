<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    protected $fillable = ['landing_page_id', 'domain'];

    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class)->onDelete('cascade');
    }
}
