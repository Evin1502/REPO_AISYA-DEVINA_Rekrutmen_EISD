<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="relative bg-paper font-sans text-on-surface">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-brand-200/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-emerald-200/30 blur-3xl"></div>
    </div>

    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <a href="{{ route('home') }}" class="group mb-8 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-xl font-extrabold text-white shadow-md shadow-brand-600/20 transition-transform duration-200 group-hover:scale-105">T</span>
            <span class="font-display text-2xl font-black tracking-tight">Tem<span class="text-brand-600">Ji</span></span>
        </a>

        <div class="w-full max-w-md animate-fade-in-up">
            @include('layouts.partials.flash')
            <div class="card shadow-dashboard">
                <div class="card-body p-6 sm:p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
