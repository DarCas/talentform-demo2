@props(['column', 'desc'])

@if(request()->query('sort') === $column)
    @if (!$desc)
        <i class="bi bi-caret-down-fill"></i>
    @else
        <i class="bi bi-caret-up-fill"></i>
    @endif
@endif
