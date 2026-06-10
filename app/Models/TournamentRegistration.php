<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentRegistration extends Pivot
{
    protected $table = 'tournament_registrations';
    protected $fillable = ['tournament_id', 'user_id', 'status'];
}
