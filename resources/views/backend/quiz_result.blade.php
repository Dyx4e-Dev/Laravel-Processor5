@extends('backend.layouts.admin')

@section('title', 'Quiz Results')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div class="welcome-info">
            <h1 class="gradient-text">Quiz Results</h1>
            <p>Daftar pengunjung yang telah menyelesaikan quiz dan status hadiah mereka.</p>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('admin.quiz_result.flush') }}" method="POST" onsubmit="return confirm('Hapus SEMUA data hasil quiz?')">
                @csrf
                <button type="submit" class="btn-neon" style="background: linear-gradient(135deg, #ff3e1d, #ff2b05); border: none; box-shadow: 0 0 15px rgba(255, 62, 29, 0.4); padding: 10px 20px;">
                    <i class='bx bx-trash'></i> Reset All Data
                </button>
            </form>
        </div>
    </div>

    <div class="card glass table-section">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="padding-right: 10px; text-align: center">No</th>
                    <th style="width: 25%;">Nama Pengunjung</th>
                    <th style="width: 25%;">Email</th>
                    <th style="width: 10%; text-align: center;">Skor</th>
                    <th style="width: 20%;">Status Hadiah</th>
                    <th style="width: 15%;">Dijelaskan Oleh</th>
                    <th style="width: 5%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $res)
                <tr>
                    <td style="color: var(--primary); font-weight: bold; text-align: center;">{{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}</td>
                    <td>
                        <p style="font-weight: bold; margin: 0;">{{ $res->nama }}</p>
                    </td>
                    <td style="color: var(--gray); font-size: 13px;">{{ $res->email }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $res->score >= 7 ? 'success' : '' }}" 
                              style="font-weight: bold; padding: 5px 10px; {{ $res->score < 7 ? 'background: #444; color: #bbb;' : '' }}">
                            {{ $res->score }}
                        </span>
                    </td>
                    <td>
                        @if($res->score >= 7)
                            <span style="color: #39FF14; font-size: 13px; font-weight: 500;">
                                <i class='bx bxs-gift'></i> {{ $res->reward_status }}
                            </span>
                        @else
                            <span style="color: var(--gray); font-size: 13px;">{{ $res->reward_status }}</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 13px; color: var(--secondary);">
                            <i class='bx bx-user-voice'></i> {{ $res->team->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <form action="{{ route('admin.quiz_result.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:transparent; border:none; color:#ff3e1d; cursor:pointer; font-size: 20px; transition: 0.3s;">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--gray); padding: 50px;">
                        <i class='bx bx-info-circle' style="font-size: 30px; display: block; margin-bottom: 10px;"></i>
                        Belum ada hasil quiz yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $results->links('pagination::bootstrap-4') }} 
    </div>
</div>
@endsection