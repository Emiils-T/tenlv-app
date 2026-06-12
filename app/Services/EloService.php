<?php
namespace App\Services;

use App\Models\User;
use App\Models\TennisMatch;
use App\Models\EloHistory;

class EloService
{
    /**
     * Aprēķina un atjauno ELO reitingus pēc mača
     */
    public function calculateAndApply(User $player1, User $player2, $winnerId, TennisMatch $match)
    {
        $kFactor = 32;

        $r1 = $player1->elo_rating;
        $r2 = $player2->elo_rating;

        $expected1 = 1 / (1 + pow(10, ($r2 - $r1) / 400));
        $expected2 = 1 / (1 + pow(10, ($r1 - $r2) / 400));

        // Nosakām faktisko rezultātu (1 = uzvara, 0 = zaudējums)
        $actual1 = ($winnerId == $player1->id) ? 1 : 0;
        $actual2 = ($winnerId == $player2->id) ? 1 : 0;

        // Jaunais reitings
        $newR1 = (int) round($r1 + $kFactor * ($actual1 - $expected1));
        $newR2 = (int) round($r2 + $kFactor * ($actual2 - $expected2));

        EloHistory::create([
            'user_id' => $player1->id,
            'tennnis_match_id' => $match->id,
            'elo_before' => $r1,
            'elo_after' => $newR1
        ]);

        EloHistory::create([
            'user_id' => $player2->id,
            'tennnis_match_id' => $match->id,
            'elo_before' => $r2,
            'elo_after' => $newR2
        ]);

        $player1->update(['elo_rating' => $newR1]);
        $player2->update(['elo_rating' => $newR2]);
    }
}
