<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{

    protected $fillable = ['name', 'index_file_path'];

    public function domain()
    {
        return $this->hasOne(Domain::class);
    }
}
