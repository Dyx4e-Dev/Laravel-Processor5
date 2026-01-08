@extends('backend.layouts.admin')

@section('title', 'Manage Benchmark')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px;">
        <div class="welcome-info">
            <h1 class="gradient-text">Benchmark Management</h1>
            <p>Kelola kategori pengujian dan skor performa untuk setiap skenario.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="notif success" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px; background: rgba(44, 232, 185, 0.1); padding: 15px; border-radius: 12px; border: 1px solid var(--primary);">
            <i class='bx bx-check-circle' style="color: var(--primary); font-size: 24px;"></i>
            <span style="color: var(--primary);">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card glass">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: var(--text); font-weight: 600;">List Kategori Benchmark</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Benchmark</th>
                    <th>Detail Skor (JSON)</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($benchmarks as $benchmark)
                <tr>
                    <td>#{{ $benchmark->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class='bx bx-bar-chart-alt-2' style="color: var(--primary); font-size: 20px;"></i>
                            <span style="font-weight: 500;">{{ $benchmark->name }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if(is_array($benchmark->scores))
                                @foreach($benchmark->scores as $core => $score)
                                    <span class="badge pending" style="font-size: 10px; padding: 2px 8px;">
                                        {{ $core }}: <strong>{{ $score }}</strong>
                                    </span>
                                @endforeach
                            @else
                                <span style="color: #666; font-size: 12px;">No data</span>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('benchmarks.edit', $benchmark->id) }}" 
                           class="btn-neon" 
                           style="padding: 6px 15px; font-size: 12px; text-decoration: none; display: inline-block;">
                            <i class='bx bx-edit-alt'></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: #666;">
                        Belum ada data benchmark.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Styling tambahan khusus untuk badge skor agar lebih compact */
    .badge.pending {
        background: rgba(59, 130, 246, 0.1);
        color: var(--secondary);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    
    .custom-table td {
        vertical-align: middle;
    }
</style>
@endsection