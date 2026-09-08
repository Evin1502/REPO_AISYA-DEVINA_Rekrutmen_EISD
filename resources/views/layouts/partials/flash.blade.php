@if (session('success'))
    <div class="animate-fade-in">
        <x-alert type="success">{{ session('success') }}</x-alert>
    </div>
@endif

<x-error-modal />
