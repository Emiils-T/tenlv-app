<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Court;
use App\Services\WeatherAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{


    // 1. READ: Parāda visus turnīrus
    public function index()
    {
        $tournaments = Tournament::with(['court', 'organiser'])->latest()->get();
        return view('tournaments.index', compact('tournaments'));
    }

    // 2. CREATE: Parāda formu jauna turnīra izveidei
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Tournament::class)) {
            abort(403, 'You are not authorized to create a tournament.');
        }
        $courts = Court::all();
        return view('tournaments.create', compact('courts'));
    }

    // 3. STORE: Saglabā jauno turnīru datubāzē
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Tournament::class)) {
            abort(403, 'You are not authorized to create a tournament.');
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'court_id' => 'required|exists:courts,id',
        ]);

        Tournament::create([
            'name' => $validatedData['name'],
            'date' => $validatedData['date'],
            'court_id' => $validatedData['court_id'],
            'status' => 'open',
            'organiser_id' => Auth::id(),
        ]);

        return redirect()->route('tournaments.index')->with('success', 'Turnīrs veiksmīgi izveidots!');
    }

    public function show(Tournament $tournament, WeatherAPIService $weatherAPIService)
    {
        $weatherForecast = $weatherAPIService->getWeather($tournament);
        $tournament->load(['players', 'tennisMatches', 'organiser']);
        $players = $tournament->players;
        $acceptedPlayers = $players->where('pivot.status', 'accepted');

        $user = Auth::id();

        // standings tabula
        $standings = [];
        foreach ($acceptedPlayers as $player) {
            $standings[$player->id] = [
                'player' => $player,
                'wins' => 0,
                'losses' => 0,
            ];
        }

        foreach ($tournament->tennisMatches as $match) {
            $p1 = $match->player1_id;
            $p2 = $match->player2_id;

            if (!isset($standings[$p1]) || !isset($standings[$p2])) continue;

            if ($match->winner_id == $p1) {
                $standings[$p1]['wins']++;
                $standings[$p2]['losses']++;
            } else {
                $standings[$p2]['wins']++;
                $standings[$p1]['losses']++;
            }
        }

        uasort($standings, fn($a, $b) => $b['wins'] <=> $a['wins']);
        return view('tournaments.show', compact(
            'tournament',
            'players',
            'standings', 'user',
            'weatherForecast'));
    }

    // Parāda formu turnīra rediģēšanai
    public function edit(Request $request, Tournament $tournament)
    {
        if ($request->user()->cannot('edit', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $courts = Court::all();
        return view('tournaments.edit', compact('tournament', 'courts'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        if ($request->user()->cannot('edit', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'court_id' => 'required|exists:courts,id',
            'status' => 'required|in:open,ongoing,finished,cancelled',
        ]);

        $tournament->update($validatedData);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Turnīra informācija atjaunota!');
    }

    public function destroy(Request $request, Tournament $tournament)
    {
        if ($request->user()->cannot('delete', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', 'Turnīrs dzēsts!');
    }
}
