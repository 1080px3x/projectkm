@if ($paginator->hasPages())
    <nav class="flex flex-wrap items-center justify-center gap-1.5" role="navigation">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost !px-3 opacity-40 cursor-not-allowed">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-ghost !px-3">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-sm text-muted">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn btn-gold !px-3">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-ghost !px-3">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-ghost !px-3">›</a>
        @else
            <span class="btn btn-ghost !px-3 opacity-40 cursor-not-allowed">›</span>
        @endif
    </nav>
@endif
