<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Court;
use App\Services\WeatherAPIService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{


    // 1. READ: Parāda visus turnīrus
    public function index(): Factory|View
    {
        $tournaments = Tournament::with(['court', 'organiser'])->latest()->get();
        return view('tournaments.index', compact('tournaments'));
    }

    // 2. CREATE: Parāda formu jauna turnīra izveidei
    public function create(Request $request): Factory|View
    {
        if ($request->user()->cannot('create', Tournament::class)) {
            abort(403, 'You are not authorized to create a tournament.');
        }
        $courts = Court::all();
        return view('tournaments.create', compact('courts'));
    }

    // 3. STORE: Saglabā jauno turnīru datubāzē
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->cannot('create', Tournament::class)) {
            abort(403, 'You are not authorized to create a tournament.');
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|afterOrEqual:today',
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

    public function show(Tournament $tournament, WeatherAPIService $weatherAPIService): Factory|View
    {
        $weatherForecast = $weatherAPIService->getWeather($tournament);
        $tournament->load(['players', 'tennisMatches', 'organiser']);
        $players = $tournament->players;

        $user = Auth::id();


        return view('tournaments.show', compact(
            'tournament',
            'players', 'user',
            'weatherForecast'));
    }

    // Parāda formu turnīra rediģēšanai
    public function edit(Request $request, Tournament $tournament): Factory|View
    {
        if ($request->user()->cannot('edit', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $courts = Court::all();
        return view('tournaments.edit', compact('tournament', 'courts'));
    }

    public function update(Request $request, Tournament $tournament): RedirectResponse
    {
        if ($request->user()->cannot('update', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|afterOrEqual:today',
            'court_id' => 'required|exists:courts,id',
            'status' => 'required|in:open,ongoing,finished,cancelled',
        ]);

        $tournament->update($validatedData);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Turnīra informācija atjaunota!');
    }

    public function destroy(Request $request, Tournament $tournament): RedirectResponse
    {
        if ($request->user()->cannot('delete', $tournament)) {
            abort(403, 'You are not authorized to edit this event.');
        }

        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', 'Turnīrs dzēsts!');
    }
}
