@if ($paginator->hasPages())
<nav class="pagination">
    @if ($paginator->onFirstPage())
        <span class="page-link" style="opacity:.4">← Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-link">← Prev</a>
    @endif
    <span style="font-size:.8rem;color:#6b7280;padding:7px 4px">Hal. {{ $paginator->currentPage() }}</span>
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-link">Next →</a>
    @else
        <span class="page-link" style="opacity:.4">Next →</span>
    @endif
</nav>
@endif
