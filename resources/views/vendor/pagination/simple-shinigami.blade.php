@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-3" role="navigation">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost opacity-40 cursor-not-allowed">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-ghost" rel="prev">Sebelumnya</a>
        @endif

        <span class="text-xs text-muted">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-ghost" rel="next">Berikutnya</a>
        @else
            <span class="btn btn-ghost opacity-40 cursor-not-allowed">Berikutnya</span>
        @endif
    </nav>
@endif
