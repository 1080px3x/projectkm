@extends('layouts.app')

@section('title', 'Profil — SHINIGAMI')

@section('content')
<div class="wrap pt-8 max-w-[760px]">
    <h1 class="text-2xl font-black">Profil</h1>

    <div class="surface mt-5 p-6 flex flex-col sm:flex-row items-start sm:items-center gap-5">
        <div class="grid h-20 w-20 shrink-0 place-items-center rounded-full bg-gradient-to-br from-gold to-[#be123c] text-2xl font-bold text-ink">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <p class="text-lg font-bold">{{ $user->name }}</p>
            <p class="text-sm text-muted">{{ $user->email }}</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="badge-gold">S1</span>
                <span class="pill !py-1 !text-[11px]">Pembaca</span>
            </div>
        </div>
        <div class="ml-auto self-center">
            <a href="{{ route('settings') }}" class="btn btn-ghost">Pengaturan</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button class="btn btn-white !py-2 !px-3 text-xs">Keluar</button>
            </form>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
        <div class="surface p-3">
            <p class="text-sm font-bold">{{ $bookmarks->count() }}</p>
            <p class="text-xs text-muted">Tersimpan</p>
        </div>
        <div class="surface p-3">
            <p class="text-sm font-bold">{{ $histories->count() }}</p>
            <p class="text-xs text-muted">Riwayat baca</p>
        </div>
    </div>

    <div class="mt-6 surface p-5">
        <h2 class="text-base font-bold mb-4">Riwayat Baca Terbaru</h2>
        @if ($histories->isEmpty())
            <p class="text-sm text-muted">Belum ada riwayat baca.</p>
        @else
            <div class="space-y-3">
                @foreach ($histories as $h)
                    <div class="flex items-center gap-4 p-2">
                        <a href="{{ route('comics.show', $h->comic->slug) }}" class="w-12 shrink-0">
                            <x-cover :comic="$h->comic" />
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('comics.show', $h->comic->slug) }}" class="text-sm font-bold hover:text-gold">{{ $h->comic->title }}</a>
                            <a href="{{ route('chapters.show', [$h->comic->slug, $h->chapter->number]) }}" class="text-xs text-muted hover:text-white">
                                {{ $h->chapter->label() }}@if ($h->chapter->title) — {{ $h->chapter->title }}@endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-5 surface p-5">
        <h2 class="text-base font-bold mb-4">Tersimpan di Pustaka</h2>
        @if ($bookmarks->isEmpty())
            <p class="text-sm text-muted">Pustaka masih kosong.</p>
        @else
            <div class="mt-6 space-y-3">
                @foreach ($bookmarks as $comic)
                    <div class="surface flex items-center gap-4 p-3">
                        <a href="{{ route('comics.show', $comic->slug) }}" class="w-16 shrink-0">
                            <x-cover :comic="$comic" class="!rounded-md" />
                        </a>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('comics.show', $comic->slug) }}" class="text-sm font-bold hover:text-gold">{{ $comic->title }}</a>
                            <p class="mt-0.5 text-xs text-muted">{{ ucfirst($comic->type) }} • {{ number_format($comic->rating, 1) }}</p>
                            @if ($chapter = $comic->chapters->first())
                                <a href="{{ route('chapters.show', [$comic->slug, $chapter->number]) }}" class="text-xs text-muted hover:text-white">
                                    {{ $chapter->label() }} — {{ $chapter->timeAgo() }}
                                </a>
                            @endif
                        </div>
                        <form action="{{ route('library.destroy', $comic) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost !py-2 !px-3 text-xs">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
