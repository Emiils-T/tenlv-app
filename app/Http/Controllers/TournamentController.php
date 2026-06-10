<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    // 1. READ: Parāda visus turnīrus
    public function index()
    {
        // Ielādē turnīrus kopā ar to kortiem un organizatoriem, lai nedarbinātu N+1 problēmu
        $tournaments = Tournament::with(['court', 'organiser'])->latest()->get();
        return view('tournaments.index', compact('tournaments'));
    }

    // 2. CREATE: Parāda formu jauna turnīra izveidei
    public function create()
    {
        // Iegūstam visus kortus, lai tos varētu izvēlēties no dropdown izvēlnes
        $courts = Court::all();
        return view('tournaments.create', compact('courts'));
    }

    // 3. STORE: Saglabā jauno turnīru datubāzē
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'court_id' => 'required|exists:courts,id',
        ]);

        Tournament::create([
            'name' => $validatedData['name'],
            'date' => $validatedData['date'],
            'court_id' => $validatedData['court_id'],
            'status' => 'open', // Pēc noklusējuma atvērts
            'organiser_id' => Auth::id(), // Piesaistām pašlaik pieslēgušos lietotāju kā organizatoru
        ]);

        return redirect()->route('tournaments.index')->with('success', 'Turnīrs veiksmīgi izveidots!');
    }

    // 4. READ (Single): Parāda viena turnīra detaļas
    public function show(Tournament $tournament)
    {
        // Ielādējam saistītos datus: spēlētājus un mačus
        $tournament->load(['court', 'organiser', 'players', 'matches']);
        return view('tournaments.show', compact('tournament'));
    }

    // 5. EDIT: Parāda formu turnīra rediģēšanai
    public function edit(Tournament $tournament)
    {
        // Drošības pārbaude: Vai pašreizējais lietotājs ir šī turnīra organizators vai admin?
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403, 'Tev nav tiesību rediģēt šo turnīru.');
        }

        $courts = Court::all();
        return view('tournaments.edit', compact('tournament', 'courts'));
    }

    // 6. UPDATE: Saglabā rediģētās izmaiņas
    public function update(Request $request, Tournament $tournament)
    {
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403, 'Tev nav tiesību rediģēt šo turnīru.');
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

    // 7. DESTROY: Dzēš turnīru
    public function destroy(Tournament $tournament)
    {
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403, 'Tev nav tiesību dzēst šo turnīru.');
        }

        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', 'Turnīrs dzēsts!');
    }
}
