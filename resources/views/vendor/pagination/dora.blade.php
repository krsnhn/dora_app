@if ($paginator->hasPages())
    <nav style="display:flex;justify-content:center;align-items:center;gap:.5rem;margin-top:2rem;">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding:.5rem 1rem;border-radius:8px;background:#f1f1f1;color:#aaa;cursor:not-allowed;">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:.5rem 1rem;border-radius:8px;background:white;color:var(--earth);border:1px solid var(--beige);text-decoration:none;">← Prev</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:.5rem .75rem;color:#aaa;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:.5rem .85rem;border-radius:8px;background:var(--forest);color:white;font-weight:600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:.5rem .85rem;border-radius:8px;background:white;color:var(--earth);border:1px solid var(--beige);text-decoration:none;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:.5rem 1rem;border-radius:8px;background:white;color:var(--earth);border:1px solid var(--beige);text-decoration:none;">Next →</a>
        @else
            <span style="padding:.5rem 1rem;border-radius:8px;background:#f1f1f1;color:#aaa;cursor:not-allowed;">Next →</span>
        @endif

    </nav>
@endif