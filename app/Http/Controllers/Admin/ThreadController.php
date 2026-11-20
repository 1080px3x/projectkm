<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thread;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::latest()->paginate(20);

        return view('admin.threads.index', compact('threads'));
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();

        return back()->with('status', 'Thread dihapus.');
    }
}
