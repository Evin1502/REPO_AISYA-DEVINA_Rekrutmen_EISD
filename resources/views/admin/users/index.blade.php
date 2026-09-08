@extends('layouts.admin')

@section('title', 'Pengguna - Admin TemJi')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-on-surface">Pengguna</h1>
            <p class="text-sm text-on-surface-variant">Daftar semua pengguna TemJi.</p>
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ $search }}"
                   class="form-control w-48" placeholder="Cari nama / email...">
            <select name="role" class="form-select w-auto">
                <option value="">Semua Role</option>
                @foreach (['resident' => 'Resident', 'admin' => 'Admin', 'collector' => 'Collector'] as $value => $label)
                    <option value="{{ $value }}" @selected($currentRole === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    @if ($users->isEmpty())
        <x-empty-state message="Tidak ada pengguna dengan filter ini." />
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-surface-container-low/80">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Poin</th>
                            <th>Terdaftar</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="font-medium">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="inline-flex rounded-full bg-surface-container px-2.5 py-0.5 text-xs font-semibold text-on-surface">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ number_format($user->points) }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
@endsection