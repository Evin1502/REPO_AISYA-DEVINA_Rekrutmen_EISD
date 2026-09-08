@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-2">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-on-surface-variant bg-surface-container-lowest border border-outline-variant cursor-not-allowed leading-5 rounded-lg">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-on-surface bg-surface-container-lowest border border-outline-variant leading-5 rounded-lg hover:border-brand-500 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/40 active:bg-brand-50 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-on-surface bg-surface-container-lowest border border-outline-variant leading-5 rounded-lg hover:border-brand-500 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/40 active:bg-brand-50 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-on-surface-variant bg-surface-container-lowest border border-outline-variant cursor-not-allowed leading-5 rounded-lg">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif