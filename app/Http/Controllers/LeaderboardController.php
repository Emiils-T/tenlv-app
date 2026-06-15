<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index() {
        $users = User::orderBy('elo_rating','desc')->paginate(15);
        return view('leaderboard.show',compact('users'));
    }
}
