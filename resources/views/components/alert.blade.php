@props(['type' => 'success', 'autoDismiss' => 7000])

@php
    $styles = [
        'success' => 'bg-green-50 text-green-800 ring-green-200',
        'error' => 'bg-red-50 text-red-800 ring-red-200',
        'info' => 'bg-blue-50 text-blue-800 ring-blue-200',
    ][$type] ?? 'bg-green-50 text-green-800 ring-green-200';
    $iconBg = [
        'success' => 'bg-green-100 text-green-700',
        'error' => 'bg-red-100 text-red-700',
        'info' => 'bg-blue-100 text-blue-700',
    ][$type] ?? 'bg-green-100 text-green-700';
    $icons = [
        'success' => '✓',
        'error' => '✕',
        'info' => 'ℹ',
    ][$type] ?? '✓';
@endphp

<div
    {{ $attributes->merge(['class' => 'animate-fade-in-up transition-all duration-500 ease-out mb-4 flex items-start gap-3 rounded-xl px-4 py-3 text-sm shadow-sm ring-1 ' . $styles]) }}
    role="alert"
    data-auto-dismiss="{{ $autoDismiss }}"
>
    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $iconBg }}">{{ $icons }}</span>
    <div class="flex-1 pt-0.5">{{ $slot }}</div>
    <button
        type="button"
        class="-mr-1 -mt-0.5 shrink-0 rounded-md p-1 text-current/60 transition-colors hover:bg-black/5 hover:text-current"
        aria-label="Tutup notifikasi"
        onclick="dismissAlert(this.closest('[role=alert]'))"
    >
        <span class="material-symbols-outlined text-base">close</span>
    </button>
</div>

<script>
    if (typeof window.dismissAlert === 'undefined') {
        window.dismissAlert = function(alertEl) {
            if (!alertEl || alertEl.dataset.dismissing === 'true') return;
            alertEl.dataset.dismissing = 'true';
            alertEl.style.opacity = '0';
            alertEl.style.transform = 'translateY(-10px)';
            alertEl.style.maxHeight = alertEl.offsetHeight + 'px';
            setTimeout(() => {
                alertEl.style.maxHeight = '0px';
                alertEl.style.paddingTop = '0px';
                alertEl.style.paddingBottom = '0px';
                alertEl.style.marginTop = '0px';
                alertEl.style.marginBottom = '0px';
                alertEl.style.overflow = 'hidden';
            }, 150);
            setTimeout(() => alertEl.remove(), 500);
        };
    }
    (function() {
        const alertElements = document.querySelectorAll('[role=alert][data-auto-dismiss]');
        alertElements.forEach(function(alertEl) {
            if (alertEl.dataset.timerSet) return;
            alertEl.dataset.timerSet = 'true';
            const delay = parseInt(alertEl.getAttribute('data-auto-dismiss'), 10) || 7000;
            if (delay > 0) {
                setTimeout(function() {
                    window.dismissAlert(alertEl);
                }, delay);
            }
        });
    })();
</script>
