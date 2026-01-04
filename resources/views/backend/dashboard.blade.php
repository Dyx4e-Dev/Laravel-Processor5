@extends('backend.layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="grid-welcome reveal">
        <div class="card glass welcome-card">
            <div class="welcome-info">
                <h1 class="gradient-text">Selamat Datang {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p>Sistem Anda berjalan optimal. Penjualan hari ini naik 72% dari target mingguan.</p>
                <button class="btn-neon">View Badges</button>
            </div>
            <div class="welcome-icon">
                <i class='bx bx-rocket floating'></i>
            </div>
        </div>

        <div class="stats-grid">
            <div class="card glass stat-card">
                <i class='bx bx-wallet icon-profit'></i>
                <div class="stat-info">
                    <span>Profit</span>
                    <h3>$12,628</h3>
                    <small class="up">+72.8%</small>
                </div>
            </div>
            <div class="card glass stat-card">
                <i class='bx bx-cart icon-sales'></i>
                <div class="stat-info">
                    <span>Sales</span>
                    <h3>$4,679</h3>
                    <small class="up">+28.4%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-charts reveal">
        <div class="card glass chart-main">
            <div class="card-header">
                <h4>Total Revenue</h4>
                <select class="glass-select">
                    <option>2024</option>
                    <option>2023</option>
                </select>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="card glass growth-card">
            <h4>Growth Report</h4>
            <div class="circular-progress">
                <div class="inner-circle">
                    <span id="progress-value">0%</span>
                </div>
            </div>
            <p>Company Growth 2024</p>
        </div>
    </div>
@endsection