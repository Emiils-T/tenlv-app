<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class RegistrationController extends Controller

{
    public function store(Request $request, Tournament $tournament) {} // Spēlētājs pievienojas
    public function update(Request $request, Tournament $tournament, $userId) {} // Organizators apstiprina/noraida
}

