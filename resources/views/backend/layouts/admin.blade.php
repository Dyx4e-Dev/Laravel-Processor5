<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'NeonCore') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <div id="loader" class="loader-wrapper">
        <div class="loader"></div>
    </div>

    @php
        $webSetting = \App\Models\WebSetting::first();
    @endphp

    <div class="dashboard-container">
        <aside class="sidebar reveal">
            <div class="logo">
                <h1>{{ $webSetting->title }}</h1>
            </div>
            
            <nav class="menu">
                <p class="menu-label">Menu Utama</p>
                <ul>
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class='bx bxs-dashboard'></i> Dashboard
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings') }}">
                            <i class='bx bxs-cog'></i> Settings
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.benchmark') ? 'active' : '' }}">
                        <a href="{{ route('admin.benchmark') }}">
                            <i class='bx bxs-bar-chart-alt-2'></i> Benchmark
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings') }}">
                            <i class='bx bxs-like'></i> Recommendation
                        </a>
                    </li>

                   <li class="{{ request()->routeIs('admin.glossary') ? 'active' : '' }}">
                        <a href="{{ route('admin.glossary') }}">
                            <i class='bx bxs-book'></i> Glosarium
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.quiz') ? 'active' : '' }}">
                        <a href="{{ route('admin.quiz') }}">
                            <i class='bx bxs-brain'></i> Quiz
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.quiz_result') ? 'active' : '' }}">
                        <a href="{{ route('admin.quiz_result') }}">
                            <i class='bx bxs-brain'></i> Quiz Results
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.team') ? 'active' : '' }}">
                        <a href="{{ route('admin.team') }}">
                            <i class='bx bxs-group'></i> Team
                        </a>
                    </li>
                </ul>

            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="btn-neon" style="cursor: pointer; width: 100%;">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </aside>

        <main class="main-content">
            <header class="navbar reveal">
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search systems...">
                </div>
                <div class="nav-actions">
                    <div class="notif-badge">
                        <i class='bx bx-bell'></i>
                        <span class="dot"></span>
                    </div>
                    <div class="user-profile" style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 13px;">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2ce8b9&color=070707" alt="Avatar" class="avatar">
                    </div>
                </div>
            </header>

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    @if(session('success'))
    <div id="notification-area">
        <div class="notif">
            <span style="color: var(--primary)">✔ Success:</span> {{ session('success') }}
        </div>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.notif').style.display = 'none';
        }, 3000);
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')
</body>
</html>