@extends('layouts.app')

@section('title', 'Leaderboard — SHINIGAMI')

@section('content')
<div class="wrap pt-8">
    <h1 class="text-2xl font-black">Leaderboard</h1>
    <p class="mt-1 text-sm text-muted">Peringkat komik berdasarkan rating pembaca.</p>

    <div class="mt-6 space-y-2">
        @foreach ($comics as $comic)
            <div class="surface flex items-center gap-4 p-3">
                <span class="w-8 text-center text-lg font-black {{ $loop->iteration <= 3 ? 'text-gold' : 'text-muted' }}">{{ $comics->firstItem() + $loop->index }}</span>
                <a href="{{ route('comics.show', $comic->slug) }}" class="w-12 shrink-0">
                    <x-cover :comic="$comic" class="!rounded-md" />
                </a>
                <div class="min-w-0 flex-1">
                    <a href="{{ route('comics.show', $comic->slug) }}" class="text-sm font-bold hover:text-gold truncate block">{{ $comic->title }}</a>
                    <p class="text-xs text-muted">{{ ucfirst($comic->type) }} · {{ $comic->status }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-lg font-black text-gold">{{ number_format($comic->rating, 1) }}</p>
                    <p class="text-[10px] text-muted">rating</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">{{ $comics->links() }}</div>
</div>
@endsection
