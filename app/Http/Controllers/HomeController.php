<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $tab = $request->query('tab', 'rekomendasi');

        $heroes = Comic::with('chapters')->orderByDesc('rating')->take(3)->get();

        $ranking = Comic::with('chapters')->orderByDesc('rating')->take(8)->get();

        $recommended = match ($tab) {
            'harian' => Comic::with('chapters')->withMax('chapters', 'released_at')->orderByDesc('chapters_max_released_at'),
            'mingguan' => Comic::with('chapters')->orderByDesc('rating'),
            'alltime' => Comic::with('chapters')->orderByDesc('id'),
            default => Comic::with('chapters')->latest(),
        };
        $recommended = $recommended->paginate(5, ['*'], 'rpage')->withQueryString();

        $grid = Comic::with(['chapters' => fn ($q) => $q->take(2)])
            ->ofType($type)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('home', compact('heroes', 'ranking', 'recommended', 'grid', 'tab', 'type'));
    }
}
