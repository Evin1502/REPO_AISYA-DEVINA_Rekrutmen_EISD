@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif
@if (session('error'))
    <x-alert type="error">{{ session('error') }}</x-alert>
@endif
@if ($errors->any())
    <x-alert type="error">
        <strong>Terjadi kesalahan input:</strong>
        <ul class="mt-1 list-disc pl-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
