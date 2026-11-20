<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::orderBy('name')->paginate(30);

        return view('admin.genres.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:60']]);

        Genre::firstOrCreate(['slug' => Str::slug($data['name'])], ['name' => $data['name']]);

        return back()->with('status', 'Genre ditambahkan.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return back()->with('status', 'Genre dihapus.');
    }
}
