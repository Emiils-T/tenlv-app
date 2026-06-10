<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index() {} // Admin lietotāju saraksts
    public function show(User $user) {} // Publiskais profils
    public function update(User $user) {} // Bloķēt/Atbloķēt (Admin)
}
