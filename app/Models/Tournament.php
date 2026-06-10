<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'date', 'status', 'organiser_id', 'court_id'];

    public function organiser() {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function court() {
        return $this->belongsTo(Court::class);
    }

    public function players() {
        return $this->belongsToMany(User::class, 'tournament_players')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function tennisMatches() {
        return $this->hasMany(TennisMatch::class);
    }
}
