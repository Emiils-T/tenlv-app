<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Tikai administratoriem ir pieeja šai lapai.');
        }

        $users = User::withTrashed()->orderBy('name')->get();

        return view('players.index', compact('users'));
    }
    public function show(User $user) {
        $user->load([ 'eloHistory.match.player1','eloHistory.match.player2']);
        return view('players.show',compact('user'));
    }
    public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        // Neļaujam adminam nejauši atņemt lomu pašam sev
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Nevar mainīt lomu pats sev!');
        }

        $validated = $request->validate([
            'role' => 'required|in:player,organiser,admin'
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Lietotāja loma ir atjaunināta!');
    }
    public function toggleBlock($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        if ($id == Auth::id()) {
            return back()->with('error', 'Nevar bloķēt pats sevi!');
        }

        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            $message = 'Lietotājs ir veiksmīgi atbloķēts.';
        } else {
            $user->delete();
            $message = 'Lietotājs ir bloķēts.';
        }

        return back()->with('success', $message);
    }
}
