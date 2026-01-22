<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Single-Core vs Multi-Core: Perbandingan Interaktif</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body>
    
    
    <!-- Loading screen -->
    <div id="loading-screen" class="loading-screen" aria-hidden="false">
        <div class="loader" role="status" aria-label="Memuat konten">
            <div class="spinner" aria-hidden="true"></div>
            <div class="loader-text ">Loading</div>
        </div>
    </div>
    <!-- Loading screen -->
    
    <!-- Notification Area -->
    <div id="notification-area"></div>
    <!-- Notification Area -->

    <!-- header -->
    @include('frontend.header')
    <!-- hero -->
    @include('frontend.hero')
    <!-- analogy -->
    @include('frontend.analogy')
    <!-- benchmark -->
    @include('frontend.benchmark')
    <!-- recommendation -->
    @include('frontend.recommendation')
    <!-- team -->
    @include('frontend.team')
    <!-- quiz -->
    @include('frontend.quiz')
    <!-- glossary -->
    @include('frontend.glossary')
    <!-- footer -->
    @include('frontend.footer')

    <!-- toTop -->
    <button class="scroll-to-top reveal" id="scrollToTop">
        <ion-icon name="arrow-up-outline"></ion-icon>
    </button>
    <!-- toTop -->

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>