<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <a href="{{ route('home') }}" class="mb-6 flex items-center gap-2 text-2xl font-bold text-brand-600">
            <span>🗑️</span> TemJi
        </a>

        @if (session('success'))
            <x-alert type="success" class="w-full max-w-md">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error" class="w-full max-w-md">{{ session('error') }}</x-alert>
        @endif

        <div class="card w-full max-w-md">
            <div class="card-body p-6 sm:p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
