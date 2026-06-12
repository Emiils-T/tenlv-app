<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller

{
    public function store(Tournament $tournament,)
    {
        if ($tournament->status !== 'open') {
            return back()->with('error', 'Šis turnīrs vairs nepieņem jaunus dalībniekus.');
        }

        if ($tournament->players()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Tu jau esi pieteicies šim turnīram.');
        }

        $tournament->players()->attach(Auth::id(), ['status' => 'pending']);

        return back()->with('success', 'Tavs pieteikums ir nosūtīts organizatoram!');

    }

    public function update(Request $request, Tournament $tournament, $userId)
    {
        if (Auth::id() !== $tournament->organiser_id && Auth::user()->role !== 'admin') {
            abort(403, 'Tev nav tiesību pārvaldīt šī turnīra dalībniekus.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $tournament->players()->updateExistingPivot($userId, [
            'status' => $validated['status']
        ]);

        $pazinojums = $validated['status'] === 'accepted' ? 'Spēlētājs apstiprināts!' : 'Pieteikums noraidīts.';

        return back()->with('success', $pazinojums);
    }
}

