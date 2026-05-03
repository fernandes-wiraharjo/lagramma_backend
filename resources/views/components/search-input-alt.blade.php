@props([
    'placeholder' => 'Search Our Product',
    'ariaLabel' => 'Search Our Product',
    'maxWidth' => '300px',
])

<div {{ $attributes->merge(['class' => 'input-group flex-nowrap lg-search-input-alt']) }} style="max-width: {{ $maxWidth }};">
    <span class="input-group-text border-0 ps-3 pe-2">
        <i class="bi bi-search" aria-hidden="true"></i>
    </span>
    <input type="text" class="form-control border-0 shadow-none ps-0 pe-3" placeholder="{{ $placeholder }}"
        aria-label="{{ $ariaLabel }}">
</div>
<script>
    document.querySelectorAll('.lg-search-input-alt input').forEach(function(input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && input.value.trim()) {
                e.preventDefault();
                window.location.href = '{{ config('app.frontend_url') }}/catalogue?search=' + encodeURIComponent(input.value.trim());
            }
        });
    });
</script>
