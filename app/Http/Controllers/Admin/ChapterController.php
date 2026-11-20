<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Comic;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function store(Request $request, Comic $comic)
    {
        $data = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'unique:chapters,number,NULL,id,comic_id,'.$comic->id],
            'title' => ['nullable', 'string', 'max:150'],
            'released_at' => ['required', 'date'],
        ]);

        $comic->chapters()->create($data);

        return back()->with('status', 'Chapter ditambahkan.');
    }

    public function update(Request $request, Chapter $chapter)
    {
        $data = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'unique:chapters,number,'.$chapter->id.',id,comic_id,'.$chapter->comic_id],
            'title' => ['nullable', 'string', 'max:150'],
            'released_at' => ['required', 'date'],
        ]);

        $chapter->update($data);

        return back()->with('status', 'Chapter diperbarui.');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return back()->with('status', 'Chapter dihapus.');
    }
}
