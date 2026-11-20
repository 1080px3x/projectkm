@extends('admin.layout')

@section('title', 'Thread')

@section('content')
<h1 class="text-xl font-black">Thread Komunitas</h1>

<div class="mt-4 space-y-2">
    @forelse ($threads as $thread)
        <article class="surface p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h2 class="text-sm font-bold">{{ $thread->title }}</h2>
                    <p class="mt-1 text-sm text-muted line-clamp-2">{{ $thread->body }}</p>
                    <p class="mt-1.5 text-[11px] text-muted">{{ $thread->author_name }} · {{ $thread->created_at->diffForHumans() }}</p>
                </div>
                <form action="{{ route('admin.threads.destroy', $thread) }}" method="POST" onsubmit="return confirm('Hapus thread?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-300 shrink-0">Hapus</button>
                </form>
            </div>
        </article>
    @empty
        <p class="surface p-6 text-center text-sm text-muted">Belum ada thread.</p>
    @endforelse
</div>

<div class="mt-6">{{ $threads->links() }}</div>
@endsection
