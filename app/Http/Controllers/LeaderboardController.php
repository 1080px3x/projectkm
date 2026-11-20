<?php

namespace App\Http\Controllers;

use App\Models\Comic;

class LeaderboardController extends Controller
{
    public function index()
    {
        $comics = Comic::with('chapters')->orderByDesc('rating')->paginate(20);

        return view('leaderboard.index', compact('comics'));
    }
}
