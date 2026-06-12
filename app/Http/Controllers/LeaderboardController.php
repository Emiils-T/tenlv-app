<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index() {
        $users = User::get()->toArray();
        uasort($users, fn($a, $b) => $b['elo_rating'] <=> $a['elo_rating']);
        return view('leaderboard.show',compact('users'));
    } // Globālais ELO reitinga saraksts
}
