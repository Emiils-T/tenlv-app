<?php

namespace App\Models;

use Database\Factories\TournamentFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UseFactory(TournamentFactory::class)]
class Tournament extends Model
{

    protected $fillable = ['name', 'date', 'status', 'organiser_id', 'court_id'];

    /** @use HasFactory<TournamentFactory> */
    use HasFactory;

    public function organiser() {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function court() {
        return $this->belongsTo(Court::class);
    }

    public function players() {
        return $this->belongsToMany(User::class, 'tournament_registrations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function tennisMatches() {
        return $this->hasMany(TennisMatch::class);
    }
}
