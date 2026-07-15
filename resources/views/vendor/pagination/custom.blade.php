@if ($paginator->hasPages())
    <nav class="pagination-container">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <span class="pagination-link disabled" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">&laquo; Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">&laquo; Prev</a>
        @endif

        <span style="color: var(--text-muted); font-size: 0.85rem; margin: 0 0.5rem;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Next &raquo;</a>
        @else
            <span class="pagination-link disabled" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Next &raquo;</span>
        @endif
    </nav>
@endif
