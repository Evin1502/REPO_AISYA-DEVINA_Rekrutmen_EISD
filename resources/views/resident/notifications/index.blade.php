@extends('layouts.app')

@section('title', 'Notifikasi - TemJi')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">Notifikasi</h1>
        <p class="text-sm text-slate-500">Pemberitahuan dari TemJi untuk Anda.</p>
    </div>

    @if ($notifications->isEmpty())
        <x-empty-state message="Tidak ada notifikasi." />
    @else
        <div class="space-y-3">
            @foreach ($notifications as $notification)
                <div class="card p-4 {{ $notification->is_read ? '' : 'ring-1 ring-brand-200 bg-brand-50/50' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-slate-900">{{ $notification->title }}</p>
                                @if (!$notification->is_read)
                                    <span class="inline-flex rounded-full bg-brand-600 px-2 py-0.5 text-xs font-semibold text-white">Baru</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                            <small class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if (!$notification->is_read)
                            <form method="POST" action="{{ route('resident.notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary btn-sm whitespace-nowrap">Tandai Dibaca</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection