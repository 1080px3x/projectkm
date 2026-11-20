@extends('layouts.app')

@section('title', 'SHINIGAMI — Baca Komik Gratis')

@section('content')
<div id="top"></div>

{{-- HERO CAROUSEL --}}
<section class="wrap pt-5">
    <div class="relative overflow-hidden rounded-2xl" data-carousel>
        @foreach ($heroes as $i => $hero)
            <div class="relative h-[300px] sm:h-[360px] md:h-[420px] {{ $i === 0 ? '' : 'hidden' }}" data-slide>
                <div class="absolute inset-0" style="background: {{ $hero->coverStyle() }};">
                    @if ($url = $hero->coverUrl())
                        <img src="{{ $url }}" alt="{{ $hero->title }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/50 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-ink/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 md:p-10">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="badge-gold">S1 ORIGINAL</span>
                        <span class="text-[11px] uppercase tracking-wider font-bold text-gold">{{ ucfirst($hero->type) }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl md:text-5xl font-black leading-tight max-w-2xl">{{ $hero->title }}</h1>
                    <div class="mt-3 flex items-center gap-4">
                        <x-stars :rating="$hero->rating" />
                        @if ($hero->chapters->first())
                            <span class="text-xs text-white/70">{{ $hero->chapters->first()->label() }}</span>
                        @endif
                    </div>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/70 line-clamp-2">{{ $hero->synopsis }}</p>
                    <a href="{{ route('comics.show', $hero->slug) }}" class="btn btn-white mt-5">Lihat detail</a>
                </div>
            </div>
        @endforeach

        <button data-prev class="icon-btn absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 backdrop-blur" aria-label="Sebelumnya">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <button data-next class="icon-btn absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 backdrop-blur" aria-label="Berikutnya">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </button>

        <div class="absolute bottom-4 right-5 flex items-center gap-1.5">
            @foreach ($heroes as $i => $hero)
                <button data-dot class="h-1.5 w-5 rounded-full transition-colors {{ $i === 0 ? 'bg-gold' : 'bg-white/30' }}" aria-label="Slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
    </div>
</section>

{{-- RANKING STRIP --}}
<section class="wrap mt-8">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold">Ranking Populer</h2>
        <a href="{{ route('leaderboard') }}" class="text-xs font-semibold text-gold hover:underline">Lihat semua</a>
    </div>
    <div class="rank-strip">
        @foreach ($ranking as $comic)
            <a href="{{ route('comics.show', $comic->slug) }}" class="w-[110px] shrink-0">
                <div class="cover">
                    <span class="cover-title">{{ $comic->title }}</span>
                </div>
                <p class="mt-2 text-xs font-semibold line-clamp-1">{{ $comic->title }}</p>
                <p class="mt-0.5 text-[11px] font-bold text-gold">★ {{ number_format($comic->rating, 1) }}</p>
            </a>
        @endforeach
    </div>
</section>

{{-- TABS + RECOMMENDED RANK LIST --}}
<section class="wrap mt-10">
    <div class="flex items-center gap-6 border-b border-line overflow-x-auto">
        @foreach (['rekomendasi' => 'Rekomendasi', 'harian' => 'Rank Harian', 'mingguan' => 'Rank Mingguan', 'alltime' => 'Rank All Time'] as $key => $label)
            <a href="{{ route('home', array_filter(['tab' => $key, 'type' => $type])) }}"
               class="tab whitespace-nowrap {{ $tab === $key ? 'tab-active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-2">
        @foreach ($recommended as $comic)
            <div class="rank-row" style="grid-template-columns: 24px 48px 1fr auto;">
                <span class="text-sm font-black text-muted">{{ $recommended->firstItem() + $loop->index }}</span>
                <a href="{{ route('comics.show', $comic->slug) }}" class="rank-thumb" style="background: {{ $comic->coverStyle() }};">
                    @if ($url = $comic->coverUrl())
                        <img src="{{ $url }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    @endif
                    <span>{{ $comic->title }}</span>
                </a>
                <div class="min-w-0">
                    <a href="{{ route('comics.show', $comic->slug) }}" class="block text-sm font-bold hover:text-gold truncate">{{ $comic->title }}</a>
                    @if ($chapter = $comic->chapters->first())
                        <a href="{{ route('chapters.show', [$comic->slug, $chapter->number]) }}" class="text-xs text-muted hover:text-white">
                            Ch.{{ $chapter->number }} · {{ $chapter->timeAgo() }}
                        </a>
                    @endif
                </div>
                <div class="text-right">
                    <x-stars :rating="$comic->rating" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $recommended->links('vendor.pagination.simple-shinigami') }}
    </div>
</section>

{{-- FILTER PILLS --}}
<section class="wrap mt-10">
    <div class="flex flex-wrap items-center gap-2">
        @foreach (['project' => 'Project', 'manhwa' => 'Manhwa', 'manhua' => 'Manhua', 'manga' => 'Manga'] as $key => $label)
            <a href="{{ route('home', array_filter(['type' => $key, 'tab' => $tab])) }}"
               class="pill {{ $type === $key ? 'pill-active' : '' }}">{{ $label }}</a>
        @endforeach
        @if ($type)
            <a href="{{ route('home', array_filter(['tab' => $tab])) }}" class="pill !border-transparent !bg-transparent text-muted hover:text-white">Reset</a>
        @endif
    </div>
</section>

{{-- GRID --}}
<section class="wrap mt-6">
    @if ($grid->isEmpty())
        <p class="py-16 text-center text-sm text-muted">Belum ada komik untuk filter ini.</p>
    @else
        <div class="grid-comics">
            @foreach ($grid as $comic)
                <x-comic-card :comic="$comic" />
            @endforeach
        </div>

        @if ($grid->hasMorePages())
            <div class="mt-8 text-center">
                <a href="{{ $grid->nextPageUrl() }}" class="btn btn-dark">Muat lainnya</a>
            </div>
        @endif
        @if ($grid->previousPageUrl())
            <div class="mt-3 text-center">
                <a href="{{ $grid->previousPageUrl() }}" class="text-xs text-muted hover:text-white">← Halaman sebelumnya</a>
            </div>
        @endif
    @endif
</section>
@endsection
