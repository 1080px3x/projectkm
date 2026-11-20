@php($r = round((float) $rating * 2) / 2)
<span class="inline-flex items-center gap-1 text-gold" title="Rating {{ number_format((float) $rating, 1) }}">
    @for ($i = 1; $i <= 5; $i++)
        <svg width="12" height="12" viewBox="0 0 24 24" fill="{{ $i <= $r ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="m12 3 2.9 5.9 6.6 1-4.8 4.6 1.1 6.5L12 18l-5.8 3 1.1-6.5L2.5 9.9l6.6-1z"/></svg>
    @endfor
    <span class="text-xs font-semibold text-white/80">{{ number_format((float) $rating, 1) }}</span>
</span>
