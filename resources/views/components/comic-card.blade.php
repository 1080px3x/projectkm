@php($chapters = $comic->chapters->take(2))
<div class="group">
    <a href="{{ route('comics.show', $comic->slug) }}" class="block">
        <x-cover :comic="$comic" class="transition-transform duration-200 group-hover:-translate-y-1" />
    </a>
    <a href="{{ route('comics.show', $comic->slug) }}" class="mt-2 block text-sm font-bold leading-snug line-clamp-2 hover:text-gold">{{ $comic->title }}</a>
    <div class="mt-1.5 space-y-1">
        @forelse ($chapters as $chapter)
            <a href="{{ route('chapters.show', [$comic->slug, $chapter->number]) }}"
               class="flex items-center justify-between gap-2 rounded-md bg-panel px-2 py-1.5 text-[11px] text-muted hover:bg-panel-2 hover:text-white">
                <span class="truncate">{{ $chapter->label() }}</span>
                <span class="shrink-0">{{ $chapter->timeAgo() }}</span>
            </a>
        @empty
            <span class="text-[11px] text-muted">Belum ada chapter</span>
        @endforelse
    </div>
</div>
