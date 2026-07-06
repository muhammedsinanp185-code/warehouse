@if ($paginator->hasPages())
    <nav class="pagination-container">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <span class="pagination-link disabled">&laquo; Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link">&laquo; Prev</a>
        @endif

        <span style="color: var(--text-muted); font-size: 0.9rem;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link">Next &raquo;</a>
        @else
            <span class="pagination-link disabled">Next &raquo;</span>
        @endif
    </nav>
@endif
