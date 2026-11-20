@props(['comic'])

<div {{ $attributes->merge(['class' => 'cover']) }} style="{{ $comic->coverStyle() }}">
    @if ($url = $comic->coverUrl())
        <img src="{{ $url }}" alt="{{ $comic->title }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
    @endif
    <span class="cover-title">{{ $comic->title }}</span>
</div>
