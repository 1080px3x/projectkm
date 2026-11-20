@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-xl font-black">Dashboard</h1>

<div class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    @foreach ([['Komik', $stats['comics'], 'admin.comics.index'], ['Chapter', $stats['chapters'], 'admin.comics.index'], ['Genre', $stats['genres'], 'admin.genres.index'], ['Bookmark', $stats['bookmarks'], null], ['Thread', $stats['threads'], 'admin.threads.index']] as [$label, $count, $route])
        <div class="surface p-4">
            <p class="text-xs text-muted">{{ $label }}</p>
            <p class="mt-1 text-2xl font-black">{{ number_format($count) }}</p>
            @if ($route)
                <a href="{{ route($route) }}" class="mt-2 inline-block text-[11px] font-semibold text-gold hover:underline">Kelola</a>
            @endif
        </div>
    @endforeach
</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
    <section class="surface p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold">Komik Terbaru</h2>
            <a href="{{ route('admin.comics.create') }}" class="text-xs font-semibold text-gold hover:underline">+ Tambah</a>
        </div>
        <div class="divide-y divide-line">
            @foreach ($latestComics as $comic)
                <div class="flex items-center justify-between gap-3 py-2">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate">{{ $comic->title }}</p>
                        <p class="text-[11px] text-muted">{{ ucfirst($comic->type) }} · ★ {{ number_format($comic->rating, 1) }}</p>
                    </div>
                    <a href="{{ route('admin.comics.edit', $comic) }}" class="text-xs text-muted hover:text-white shrink-0">Edit</a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="surface p-4">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold">Thread Terbaru</h2>
            <a href="{{ route('admin.threads.index') }}" class="text-xs font-semibold text-gold hover:underline">Semua</a>
        </div>
        <div class="divide-y divide-line">
            @foreach ($latestThreads as $thread)
                <div class="py-2">
                    <p class="text-sm font-semibold truncate">{{ $thread->title }}</p>
                    <p class="text-[11px] text-muted">{{ $thread->author_name }} · {{ $thread->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
