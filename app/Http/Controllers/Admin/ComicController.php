<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $comics = Comic::query()
            ->search($request->query('q'))
            ->withCount('chapters')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.comics.index', compact('comics'));
    }

    public function create()
    {
        return view('admin.comics.form', ['comic' => new Comic(['type' => 'manhwa', 'status' => 'Ongoing', 'rating' => 0])]);
    }

    public function store(Request $request)
    {
        $data = Arr::except($this->validated($request), ['cover_url', 'cover_gradient', 'cover_file']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['title']);
        $data['cover'] = $this->resolveCover($request, null);

        Comic::create($data);

        return redirect()->route('admin.comics.index')->with('status', 'Komik ditambahkan.');
    }

    public function edit(Comic $comic)
    {
        $comic->load(['chapters.images']);

        return view('admin.comics.form', compact('comic'));
    }

    public function update(Request $request, Comic $comic)
    {
        $data = Arr::except($this->validated($request), ['cover_url', 'cover_gradient', 'cover_file']);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['title'], $comic->id);
        $data['cover'] = $this->resolveCover($request, $comic->cover);

        $comic->update($data);

        return redirect()->route('admin.comics.index')->with('status', 'Komik diperbarui.');
    }

    public function destroy(Comic $comic)
    {
        $comic->delete();

        return back()->with('status', 'Komik dihapus.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150'],
            'type' => ['required', 'in:manhwa,manhua,manga,project'],
            'status' => ['required', 'string', 'max:30'],
            'rating' => ['required', 'numeric', 'min:0', 'max:10'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_gradient' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'max:2048'],
            'synopsis' => ['required', 'string'],
            'genres' => ['required', 'string', 'max:255'],
        ]);
    }

    protected function resolveCover(Request $request, ?string $fallback): string
    {
        if ($request->hasFile('cover_file')) {
            return Storage::disk('public')->putFile('covers', $request->file('cover_file'));
        }

        if ($url = trim((string) $request->input('cover_url'))) {
            return $url;
        }

        if ($gradient = trim((string) $request->input('cover_gradient'))) {
            return $gradient;
        }

        return $fallback ?: 'linear-gradient(135deg,#4c1d95,#be123c)';
    }

    protected function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 1;

        while (Comic::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
