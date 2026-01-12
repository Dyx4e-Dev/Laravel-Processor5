<section id="benchmark" class="benchmark-section">
    <div class="container">
        <h2 class="section-title reveal">Simulasi Benchmark</h2>
        <div class="benchmark-container">
            <div class="benchmark-controls reveal">
                <div class="workload-selector">
                    <button class="workload-btn active" data-workload="gaming">Gaming</button>
                    <button class="workload-btn" data-workload="video-editing">Video Editing</button>
                    <button class="workload-btn" data-workload="web-browsing">Web Browsing</button>
                </div>
                <button id="run-benchmark" class="btn">Jalankan Benchmark</button>
            </div>
            <div class="benchmark-results reveal">
                <div id="benchmark-chart"></div>
            </div>
            <div class="benchmark-explanation reveal">
                <p class="text-white">Benchmark ini mensimulasikan bagaimana performa CPU dengan jumlah core yang berbeda dalam menangani berbagai jenis workload. Perhatikan bahwa workload yang dapat diparalelkan (seperti rendering video) akan mendapatkan manfaat lebih besar dari core yang lebih banyak.</p>
            </div>
        </div>
    </div>
</section>

<script>
const benchmarkData = {};
const benchmarkResults = @json($benchmark);

@foreach($benchmark as $b)
benchmarkData['{{ strtolower(str_replace(' ', '-', $b->name)) }}'] = @json($b->scores);
@endforeach
</script>

<div class="benchmark-results-popup" id="benchmarkPopup">
    <div class="benchmark-results-content">

        <!-- HEADER -->
        <div class="benchmark-results-header">
            <h3>Hasil Benchmark</h3>
            <div class="workload-type" id="popupWorkload"></div>
        </div>

        <!-- 1. REKOMENDASI CORE -->
        <div class="reco-core-list">
            <h4>Rekomendasi Core Terbaik</h4>
            <div id="bestCoreList"></div>
        </div>

        <!-- 2. PERFORMA CORE 1–8 -->
        <div class="core-performance-list">
            <h4>Perbandingan Performa Core 1–8</h4>
            <div id="corePerformanceList"></div>
        </div>

        <!-- 3. CPU REKOMENDASI -->
        <div class="cpu-reco-list">
            <h4>Rekomendasi CPU Spesifik</h4>
            <div id="cpuRecommendationList"></div>
        </div>

        <!-- 4. ANALISIS -->
        <div class="analysis-section">
            <h4>Analisis</h4>
            <div id="benchmarkAnalysis"></div>
        </div>

        <div class="benchmark-results-footer">
            <button class="close-results-btn" id="closeBenchmarkBtn">Tutup Hasil</button>
        </div>

    </div>
</div>
