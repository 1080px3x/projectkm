<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Thread;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'comics' => Comic::count(),
            'chapters' => Chapter::count(),
            'genres' => Genre::count(),
            'bookmarks' => Bookmark::count(),
            'threads' => Thread::count(),
        ];

        $latestComics = Comic::latest()->take(5)->get();
        $latestThreads = Thread::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestComics', 'latestThreads'));
    }
}
