@extends('layouts.app')

@section('title', $comic->title.' — SHINIGAMI')

@section('content')
<div class="wrap pt-8">
    <div class="flex flex-col sm:flex-row gap-6">
        <div class="w-40 sm:w-52 shrink-0">
            <x-cover :comic="$comic" class="rounded-xl" />
            <form action="{{ route('library.store', $comic) }}" method="POST" class="mt-3">
                @csrf
                <button class="btn w-full {{ $bookmarked ? 'btn-ghost' : 'btn-gold' }}">
                    {{ $bookmarked ? 'Tersimpan' : '+ Bookmark' }}
                </button>
            </form>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <span class="badge-gold">S1</span>
                <span class="text-[11px] uppercase tracking-wider font-bold text-gold">{{ ucfirst($comic->type) }}</span>
                <span class="text-xs text-muted">•</span>
                <span class="text-xs text-muted">{{ $comic->status }}</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-3xl font-black leading-tight">{{ $comic->title }}</h1>
            <div class="mt-2"><x-stars :rating="$comic->rating" /></div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($comic->genreList() as $genre)
                    <span class="rounded-full border border-line bg-panel px-3 py-1 text-xs text-muted">{{ $genre }}</span>
                @endforeach
            </div>

            <p class="mt-5 text-sm leading-relaxed text-muted">{{ $comic->synopsis }}</p>

            <div class="mt-6 flex flex-wrap gap-2">
                @if ($first = $comic->chapters->last())
                    <a href="{{ route('chapters.show', [$comic->slug, $first->number]) }}" class="btn btn-white">Baca chapter pertama</a>
                @endif
                @if ($resume && $resume->chapter)
                    <a href="{{ route('chapters.show', [$comic->slug, $resume->chapter->number]) }}" class="btn btn-gold">
                        Lanjutkan {{ $resume->chapter->label() }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <section class="mt-8 surface p-5">
        <h2 class="text-base font-bold">Beri Rating</h2>
        @auth
            <form method="POST" action="{{ route('comics.rate', $comic) }}" class="mt-3 flex flex-wrap items-center gap-3">
                @csrf
                <select name="rating" class="field !py-2 !w-28">
                    @for ($i = 10; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((int) $userRating === $i)>{{ $i }}</option>
                    @endfor
                </select>
                <button class="btn btn-gold !py-2">Simpan Rating</button>
                @if ($userRating)
                    <span class="text-xs text-muted">Rating kamu: {{ number_format($userRating, 1) }}/10</span>
                @endif
            </form>
        @else
            <p class="mt-2 text-sm text-muted">
                <a href="{{ route('login') }}" class="text-gold hover:underline">Masuk</a> untuk memberi rating.
            </p>
        @endauth
    </section>

    <section class="mt-10">
        <h2 class="text-base font-bold mb-3">Daftar Chapter <span class="text-muted font-normal">({{ $comic->chapters->count() }})</span></h2>
        <div class="surface divide-y divide-line overflow-hidden">
            @forelse ($comic->chapters as $chapter)
                <a href="{{ route('chapters.show', [$comic->slug, $chapter->number]) }}"
                   class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-panel-2">
                    <span class="text-sm font-semibold">{{ $chapter->label() }}@if ($chapter->title) <span class="text-muted font-normal">— {{ $chapter->title }}</span>@endif</span>
                    <span class="text-xs text-muted shrink-0">{{ $chapter->timeAgo() }}</span>
                </a>
            @empty
                <p class="px-4 py-6 text-sm text-muted">Belum ada chapter.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
