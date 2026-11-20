<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $threads = Thread::latest()->paginate(10);

        return view('community.index', compact('threads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:2000'],
            'author_name' => ['required', 'string', 'max:60'],
        ]);

        Thread::create($data);

        return redirect()->route('community.index')->with('status', 'Thread berhasil dibuat.');
    }
}
