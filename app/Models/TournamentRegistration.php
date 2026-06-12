<?php

namespace App\Models;


class TournamentRegistration extends Pivot
{
    protected $table = 'tournament_registrations';
    protected $fillable = ['tournament_id', 'user_id', 'status'];
}
