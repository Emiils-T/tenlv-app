<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EloHistory extends Model
{
    protected $table = 'elo_history';
    protected $fillable = ['user_id', 'match_id', 'elo_before', 'elo_after'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function match() {
        return $this->belongsTo(TennisMatch::class);
    }
}
