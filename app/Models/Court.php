<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    protected $fillable = ['name', 'address', 'surface_type'];

    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }
}
