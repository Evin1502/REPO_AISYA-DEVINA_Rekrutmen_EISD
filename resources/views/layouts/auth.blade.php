<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-paper font-sans text-on-surface">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-xl font-extrabold text-white shadow-md shadow-brand-600/20">T</span>
            <span class="font-display text-2xl font-black tracking-tight">Tem<span class="text-brand-600">Ji</span></span>
        </a>

        <div class="w-full max-w-md">
            @include('layouts.partials.flash')
            <div class="card">
                <div class="card-body p-6 sm:p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
