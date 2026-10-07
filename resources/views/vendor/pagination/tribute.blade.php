@if ($paginator->hasPages())
<nav class="tp-nav" role="navigation" aria-label="Tribute pages">
  @if ($paginator->onFirstPage())
    <span class="tp-btn is-disabled" aria-hidden="true">&lsaquo;</span>
  @else
    <a class="tp-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">&lsaquo;</a>
  @endif

  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="tp-dots">{{ $element }}</span>
    @endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="tp-btn is-current" aria-current="page">{{ $page }}</span>
        @else
          <a class="tp-btn" href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  @if ($paginator->hasMorePages())
    <a class="tp-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">&rsaquo;</a>
  @else
    <span class="tp-btn is-disabled" aria-hidden="true">&rsaquo;</span>
  @endif
</nav>
@endif
