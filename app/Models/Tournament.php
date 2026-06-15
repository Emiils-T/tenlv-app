<?php

namespace App\Models;

use Database\Factories\TournamentFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseFactory(TournamentFactory::class)]
class Tournament extends Model
{

    protected $fillable = ['name', 'date', 'status', 'organiser_id', 'court_id'];

    /** @use HasFactory<TournamentFactory> */
    use HasFactory;

    public function organiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tournament_registrations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function tennisMatches(): HasMany
    {
        return $this->hasMany(TennisMatch::class);
    }
}
