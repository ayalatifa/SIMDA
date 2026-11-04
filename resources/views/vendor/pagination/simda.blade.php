@if ($paginator->hasPages())
<div class="pager">
    <div class="info">Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</div>
    <nav class="pages" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pg off" aria-disabled="true">‹</span>
        @else
            <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg off">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg current" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">›</a>
        @else
            <span class="pg off" aria-disabled="true">›</span>
        @endif
    </nav>
</div>
@endif
