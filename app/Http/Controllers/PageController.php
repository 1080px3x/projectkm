<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        $bookmarks = Comic::whereIn('id', $user->bookmarks()->pluck('comic_id'))
            ->with('chapters')
            ->get();

        $histories = $user->readingHistories()
            ->with(['comic', 'chapter'])
            ->take(10)
            ->get();

        return view('pages.profile', compact('user', 'bookmarks', 'histories'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.auth()->id()],
        ]);

        auth()->user()->update($data);

        return back()->with('status', 'Profil diperbarui.');
    }

    public function settings()
    {
        return view('pages.settings', ['user' => auth()->user()]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update(['password' => $data['password']]);

        return back()->with('status', 'Password diperbarui.');
    }

    public function help()
    {
        return view('pages.help');
    }
}
