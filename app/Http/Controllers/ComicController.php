<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Genre;
use App\Models\Rating;
use App\Models\ReadingHistory;
use App\Support\Bookmarks;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $comics = Comic::query()
            ->search($request->query('q'))
            ->ofType($request->query('type'))
            ->withCount('chapters')
            ->latest()
            ->paginate(18)
            ->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('comics.index', [
            'comics' => $comics,
            'genres' => $genres,
            'q' => $request->query('q'),
            'type' => $request->query('type'),
        ]);
    }

    public function show(string $slug)
    {
        $comic = Comic::with('chapters')->where('slug', $slug)->firstOrFail();

        $bookmarked = Bookmarks::has($comic->id);

        $userRating = auth()->check()
            ? Rating::where('user_id', auth()->id())->where('comic_id', $comic->id)->value('rating')
            : null;

        $resume = auth()->check()
            ? ReadingHistory::with('chapter')->where('user_id', auth()->id())->where('comic_id', $comic->id)->first()
            : null;

        return view('comics.show', compact('comic', 'bookmarked', 'userRating', 'resume'));
    }

    public function rate(Request $request, Comic $comic)
    {
        $data = $request->validate([
            'rating' => ['required', 'numeric', 'min:1', 'max:10'],
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'comic_id' => $comic->id],
            ['rating' => $data['rating']]
        );

        $avg = Rating::where('comic_id', $comic->id)->avg('rating');

        if ($avg !== null) {
            $comic->update(['rating' => round($avg, 1)]);
        }

        return back()->with('status', 'Rating tersimpan.');
    }
}
