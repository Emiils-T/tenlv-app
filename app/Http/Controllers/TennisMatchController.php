<?php

namespace App\Http\Controllers;

use App\Models\TennisMatch;
use Illuminate\Http\Request;

class TennisMatchController extends Controller
{
    public function store(Request $request) {}
    public function update(Request $request, TennisMatch $match) {} // Rezultāta ievade aktivizē ELO servisu
}
