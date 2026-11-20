<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\ReadingHistory;

class ChapterController extends Controller
{
    public function show(string $slug, int $number)
    {
        $comic = Comic::with('chapters.images')->where('slug', $slug)->firstOrFail();

        $chapter = $comic->chapters->firstWhere('number', $number);
        abort_unless($chapter, 404);

        if (auth()->check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => auth()->id(), 'comic_id' => $comic->id],
                ['chapter_id' => $chapter->id]
            );
        }

        $prev = $comic->chapters->where('number', '<', $number)->sortByDesc('number')->first();
        $next = $comic->chapters->where('number', '>', $number)->sortBy('number')->first();

        return view('chapters.show', compact('comic', 'chapter', 'prev', 'next'));
    }
}
