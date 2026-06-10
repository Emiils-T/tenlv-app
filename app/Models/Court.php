<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = ['name', 'address', 'surface_type'];

    public function tournaments() {
        return $this->hasMany(Tournament::class);
    }
}
