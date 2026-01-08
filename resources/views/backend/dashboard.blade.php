@extends('backend.layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')

<style>
    /* Styling khusus Dashboard */
    .stats-grid-6 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .grid-main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    .alert-glass {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
    }
    .alert-glass.warning { background: rgba(255, 166, 0, 0.15); border-left: 5px solid #ffa600; color: #ffebcc; }
    .alert-glass.danger { background: rgba(255, 71, 71, 0.15); border-left: 5px solid #ff4747; color: #ffe5e5; }
    
    .stat-card i { font-size: 28px; margin-bottom: 10px; display: block; }
    .icon-quiz { color: #4facfe; }
    .icon-participants { color: #00f2fe; }
    .icon-results { color: #f093fb; }
    .icon-team { color: #f5576c; }
    .icon-glossary { color: #43e97b; }
    .icon-benchmark { color: #fa709a; }

    .activity-list { display: flex; flex-direction: column; gap: 15px; margin-top: 15px; }
    .activity-item { display: flex; gap: 12px; align-items: flex-start; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px; }
    .activity-avatar { width: 35px; height: 35px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; border: 1px solid rgba(255,255,255,0.1); }
    .activity-info p { font-size: 13px; margin: 2px 0; color: rgba(255,255,255,0.8); }
    .activity-info small { color: rgba(255,255,255,0.4); font-size: 11px; }

    .glass-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .glass-table th { text-align: left; padding: 12px; color: rgba(255,255,255,0.5); font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .glass-table td { padding: 12px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    
    .badge { padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: bold; }
    .bg-success { background: rgba(44, 232, 185, 0.2); color: #2ce8b9; }
    .bg-secondary { background: rgba(255, 255, 255, 0.1); color: #bbb; }
    /* Tambahkan di dalam <style> Anda */
.chart-container {
    position: relative;
    transition: opacity 0.3s ease;
}
.chart-loading {
    opacity: 0.3; /* Memberikan efek redup saat loading */
    pointer-events: none;
}

    @media (max-width: 992px) {
        .grid-main-content { grid-template-columns: 1fr; }
    }
</style>

<div class="reveal">

    <div class="stats-grid-6">
        <div class="card glass stat-card">
            <i class='bx bx-book-content icon-quiz'></i>
            <div class="stat-info">
                <span style="font-size: 12px; opacity: 0.7;">Total Quiz</span>
                <h3 style="margin-top: 5px;">{{ $stats['total_quiz'] }}</h3>
            </div>
        </div>
        <div class="card glass stat-card">
            <i class='bx bx-group icon-participants'></i>
            <div class="stat-info">
                <span style="font-size: 12px; opacity: 0.7;">Total Peserta</span>
                <h3 style="margin-top: 5px;">{{ $stats['total_peserta'] }}</h3>
            </div>
        </div>
        <div class="card glass stat-card">
            <i class='bx bx-medal icon-results'></i>
            <div class="stat-info">
                <span style="font-size: 12px; opacity: 0.7;">Hasil Quiz</span>
                <h3 style="margin-top: 5px;">{{ $stats['total_hasil'] }}</h3>
            </div>
        </div>
        <div class="card glass stat-card">
            <i class='bx bx-user-voice icon-team'></i>
            <div class="stat-info">
                <span style="font-size: 12px; opacity: 0.7;">Total Team</span>
                <h3 style="margin-top: 5px;">{{ $stats['total_team'] }}</h3>
            </div>
        </div>

    </div>

    <div class="grid-main-content">
        <div class="card glass chart-section">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class='bx bx-line-chart'></i> Partisipasi Quiz</h4>
                
                <div class="filter-box">
                    <label style="color: rgba(255,255,255,0.5); font-size: 12px; margin-right: 8px;">Pilih Tanggal:</label>
                    <input type="date" id="singleDate" 
                        value="{{ date('Y-m-d') }}"
                        class="form-control-sm" 
                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 5px; padding: 5px;">
                </div>
            </div>
            <div class="chart-container" style="height: 300px; margin-top: 20px;">
                <canvas id="quizChart"></canvas>
            </div>
        </div>

        <div class="card glass activity-section">
            <h4 style="display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-history' style="color: var(--secondary);"></i> Recent Admin Activities
            </h4>
            <div class="activity-list">
                @foreach($recent_activities as $log)
                <div class="activity-item">
                    <div class="activity-avatar">{{ substr($log->admin_name, 0, 1) }}</div>
                    <div class="activity-info">
                        <strong>{{ $log->admin_name }}</strong>
                        <p>{{ $log->activity }}</p>
                        <small>{{ $log->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card glass table-section" style="margin-top: 25px;">
        <div class="card-header">
            <h4 style="display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-trophy' style="color: #ffab00;"></i> Top Quiz Results
            </h4>
        </div>
        <div class="table-responsive">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Yang Menjelaskan</th>
                        <th>Nilai</th>
                        <th>Reward Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quiz_results as $result)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $result->nama }}</td>
                        <td>{{ $result->email }}</td>
                        <td>{{ $result->team->name }}</td>
                        <td>{{ $result->score }}</td>
                        <td>
                            @if($result->score >= 7)
                                <span class="badge bg-success">{{ $result->reward_status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $result->reward_status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('quizChart').getContext('2d');
    let quizChart;

    function renderChart(labels, data) {
        if (quizChart) quizChart.destroy();

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(44, 232, 185, 0.4)');
        gradient.addColorStop(1, 'rgba(44, 232, 185, 0)');

        quizChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Partisipasi',
                    data: data,
                    borderColor: '#2ce8b9',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#888' } },
                    x: { grid: { display: false }, ticks: { color: '#888' } }
                }
            }
        });
    }

    // Inisialisasi awal
    renderChart({!! json_encode($chart_labels) !!}, {!! json_encode($chart_data) !!});

    // Aksi saat tanggal diubah
    document.getElementById('singleDate').addEventListener('change', function() {
        const dateValue = this.value;

        fetch(`/admin/dashboard/filter-chart?date=${dateValue}`)
            .then(response => response.json())
            .then(res => {
                renderChart(res.chart_labels, res.chart_data);
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection