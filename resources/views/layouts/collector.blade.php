<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kolektor - TemJi')</title>
    @include('layouts.partials.head-assets')
</head>
<body class="bg-slate-50 text-slate-900">
    @php
        $current = request()->route()?->getName();
    @endphp

    <div class="min-h-screen lg:flex">
        <div class="sticky top-0 z-30 flex items-center justify-between bg-collector-600 px-4 py-3 text-white lg:hidden">
            <a href="{{ route('collector.dashboard') }}" class="font-bold">🗑️ TemJi</a>
            <button id="hamburger" class="text-white" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-collector-600 transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-2 border-b border-white/10 px-5">
                <a href="{{ route('collector.dashboard') }}" class="text-lg font-bold text-white">🗑️ TemJi</a>
                <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold text-white">Kolektor</span>
            </div>
            <div class="flex-1 px-3 py-4">
                <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-collector-100">Menu</p>
                <nav class="space-y-1">
                    <a href="{{ route('collector.dashboard') }}" class="sidebar-link {{ $current === 'collector.dashboard' ? 'sidebar-link-active bg-collector-700' : 'sidebar-link-inactive' }}">📊 Panel</a>
                    <a href="{{ route('collector.pickup-requests.index') }}" class="sidebar-link {{ str_starts_with($current, 'collector.pickup-requests') ? 'sidebar-link-active bg-collector-700' : 'sidebar-link-inactive' }}">🚚 Antrean Penjemputan</a>
                    <a href="{{ route('collector.history') }}" class="sidebar-link {{ $current === 'collector.history' ? 'sidebar-link-active bg-collector-700' : 'sidebar-link-inactive' }}">📜 Riwayat</a>
                </nav>
            </div>
        </aside>

        <div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
                <h1 class="text-lg font-bold text-slate-900">@yield('title', 'Kolektor')</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-slate-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-4 lg:p-8">
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

                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.partials.sidebar-toggle')
    @stack('scripts')
</body>
</html>
