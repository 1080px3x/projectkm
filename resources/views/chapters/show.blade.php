@extends('layouts.app')

@section('title', $comic->title.' '.$chapter->label().' — SHINIGAMI')

@section('content')
<div class="wrap pt-6 max-w-[820px]">
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('comics.show', $comic->slug) }}" class="text-sm text-muted hover:text-white">&larr; Kembali ke Detail</a>
        <span class="text-xs text-muted">{{ $chapter->timeAgo() }}</span>
    </div>

    <h1 class="mt-2 text-lg sm:text-xl font-black">{{ $comic->title }}</h1>
    <p class="text-sm text-muted">{{ $chapter->label() }}@if ($chapter->title) — {{ $chapter->title }}@endif</p>

    <div class="mt-5 space-y-1.5">
        @if ($chapter->images->isNotEmpty())
            @foreach ($chapter->images as $image)
                <img src="{{ $image->url() }}" alt="Halaman {{ $loop->iteration }}" loading="lazy"
                     class="w-full rounded-lg border border-line bg-panel">
            @endforeach
        @else
            @for ($page = 1; $page <= 6; $page++)
                <div class="relative flex items-center justify-center overflow-hidden rounded-lg border border-line"
                     style="aspect-ratio: 3 / 4; background: {{ $comic->coverStyle() }};">
                    @if ($url = $comic->coverUrl())
                        <img src="{{ $url }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-40">
                    @endif
                    <span class="relative text-sm font-bold text-white/70">Halaman {{ $page }}</span>
                </div>
            @endfor
            <p class="text-center text-xs text-muted">Gambar chapter belum diunggah admin.</p>
        @endif
    </div>

    <div class="mt-6 flex items-center justify-between gap-3">
        @if ($prev)
            <a href="{{ route('chapters.show', [$comic->slug, $prev->number]) }}" class="btn btn-ghost">&larr; Chapter Sebelumnya</a>
        @else
            <span class="btn btn-ghost opacity-40 cursor-not-allowed">&larr; Chapter Sebelumnya</span>
        @endif

        <a href="{{ route('comics.show', $comic->slug) }}" class="btn btn-dark hidden sm:inline-flex">Detail</a>

        @if ($next)
            <a href="{{ route('chapters.show', [$comic->slug, $next->number]) }}" class="btn btn-gold">Chapter Berikutnya &rarr;</a>
        @else
            <span class="btn btn-ghost opacity-40 cursor-not-allowed">Chapter Berikutnya &rarr;</span>
        @endif
    </div>

    <details class="surface mt-6 p-4">
        <summary class="cursor-pointer text-sm font-bold">Daftar Chapter ({{ $comic->chapters->count() }})</summary>
        <div class="mt-3 max-h-[320px] overflow-y-auto divide-y divide-line">
            @foreach ($comic->chapters as $item)
                <a href="{{ route('chapters.show', [$comic->slug, $item->number]) }}"
                   class="flex items-center justify-between gap-3 px-1 py-2 text-sm hover:text-gold {{ $item->id === $chapter->id ? 'text-gold font-bold' : '' }}">
                    <span>{{ $item->label() }}@if ($item->title) <span class="text-muted font-normal">— {{ $item->title }}</span>@endif</span>
                    <span class="text-xs text-muted shrink-0">{{ $item->timeAgo() }}</span>
                </a>
            @endforeach
        </div>
    </details>
</div>
@endsection
