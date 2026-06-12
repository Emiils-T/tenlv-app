<?php

namespace App\Http\Controllers;

use App\Models\TennisMatch;
use App\Models\Tournament;
use App\Models\User;
use App\Services\EloService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TennisMatchController extends Controller
{
    public function create(Tournament $tournament)
    {
        // Drošība: Tikai organizators vai admin var pievienot rezultātus
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403, 'Tikai organizators var pievienot mačus.');
        }

        // Iegūstam TIKAI apstiprinātos spēlētājus šim turnīram
        $players = $tournament->players()->wherePivot('status', 'accepted')->get();

        return view('tennisMatches.create', compact('tournament', 'players'));
    }
    // Mača saglabāšana un ELO aprēķins
    public function store(Request $request, Tournament $tournament, EloService $eloService)
    {
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'player1_id' => 'required|exists:users,id|different:player2_id',
            'player2_id' => 'required|exists:users,id',
            'winner_id' => 'required|exists:users,id',
            'score' => 'required|string|max:50',
        ]);

        // Pārbaudām, vai uzvarētājs ir viens no spēlētājiem
        if (!in_array($validated['winner_id'], [$validated['player1_id'], $validated['player2_id']])) {
            return back()->withErrors(['winner_id' => 'Uzvarētājam ir jābūt vienam no izvēlētajiem spēlētājiem.'])->withInput();
        }

        // Izveidojam maču datubāzē
        $match = TennisMatch::create([
            'tournament_id' => $tournament->id,
            'player1_id' => $validated['player1_id'],
            'player2_id' => $validated['player2_id'],
            'winner_id' => $validated['winner_id'],
            'score' => $validated['score'],
        ]);

        // Ielādējam spēlētāju modeļus ELO aprēķinam
        $player1 = User::find($validated['player1_id']);
        $player2 = User::find($validated['player2_id']);

        // Izsaucam mūsu ELO servisu!
        $eloService->calculateAndApply($player1, $player2, $validated['winner_id'], $match);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Mača rezultāts saglabāts un ELO atjaunots!');
    }
}
