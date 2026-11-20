@extends('layouts.app')

@section('title', 'Pustaka — SHINIGAMI')

@section('content')
<div class="wrap pt-8">
    <h1 class="text-2xl font-black">Pustaka Saya</h1>
    <p class="mt-1 text-sm text-muted">Komik yang kamu bookmark akan muncul di sini.</p>

    @if ($comics->isEmpty())
        <div class="surface mt-6 p-12 text-center">
            <p class="text-sm text-muted">Pustaka masih kosong.</p>
            <a href="{{ route('comics.index') }}" class="btn btn-gold mt-4">Jelajahi Komik</a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach ($comics as $comic)
                <div class="surface flex items-center gap-4 p-3">
                    <a href="{{ route('comics.show', $comic->slug) }}" class="w-16 shrink-0">
                        <x-cover :comic="$comic" class="!rounded-md" />
                    </a>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('comics.show', $comic->slug) }}" class="text-sm font-bold hover:text-gold">{{ $comic->title }}</a>
                        <p class="mt-0.5 text-xs text-muted">{{ ucfirst($comic->type) }} · ★ {{ number_format($comic->rating, 1) }}</p>
                        @if ($chapter = $comic->chapters->first())
                            <a href="{{ route('chapters.show', [$comic->slug, $chapter->number]) }}" class="text-xs text-muted hover:text-white">
                                {{ $chapter->label() }} · {{ $chapter->timeAgo() }}
                            </a>
                        @endif
                    </div>
                    <form action="{{ route('library.destroy', $comic) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-ghost !py-2 !px-3 text-xs">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
