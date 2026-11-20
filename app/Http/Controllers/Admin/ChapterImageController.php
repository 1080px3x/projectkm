<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterImageController extends Controller
{
    public function index(Chapter $chapter)
    {
        $chapter->load(['comic', 'images']);

        return view('admin.chapters.images', compact('chapter'));
    }

    public function store(Request $request, Chapter $chapter)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $order = (int) $chapter->images()->max('order');

        foreach ($request->file('images') as $file) {
            $path = $file->store('chapters/'.$chapter->id, 'public');

            $chapter->images()->create([
                'image_path' => $path,
                'order' => ++$order,
            ]);
        }

        return back()->with('status', 'Gambar chapter diunggah.');
    }

    public function destroy(ChapterImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('status', 'Gambar dihapus.');
    }
}
