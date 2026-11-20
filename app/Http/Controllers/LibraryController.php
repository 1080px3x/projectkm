<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Support\Bookmarks;

class LibraryController extends Controller
{
    public function index()
    {
        $comics = Comic::with('chapters')
            ->whereIn('id', Bookmarks::query()->pluck('comic_id'))
            ->get();

        return view('library.index', compact('comics'));
    }

    public function store(Comic $comic)
    {
        Bookmarks::toggle($comic->id);

        return back()->with('status', 'Pustaka diperbarui.');
    }

    public function destroy(Comic $comic)
    {
        Bookmarks::query()->where('comic_id', $comic->id)->delete();

        return back()->with('status', 'Komik dihapus dari pustaka.');
    }
}
