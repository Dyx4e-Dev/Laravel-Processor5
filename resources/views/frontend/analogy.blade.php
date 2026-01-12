<section id="analogy" class="analogy-section">
    <div class="container">
        <h2 class="section-title reveal">Analogi: Kasir di Supermarket</h2>
        <div class="analogy-container">
            <div class="analogy-controls reveal">
                <button id="add-customer" class="btn">Tambah Pelanggan</button>
                <button id="start-simulation" class="btn">Mulai Simulasi</button>
                <button id="reset-simulation" class="btn">Reset</button>
            </div>
            <div class="supermarket-visualization reveal">
                <div class="core-type">
                    <h3 class="core-title">Single-Core (1 Kasir)</h3>
                    <div class="checkout-area">
                        <div class="cashiers-container single-cashier">
                            <div class="cashier text-white">Kasir</div>
                        </div>
                        <div class="queue" id="single-queue">
                            <!-- Pelanggan akan ditambahkan di sini -->
                        </div>
                    </div>
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-value" id="single-customers">0</div>
                            <div class="text-white">Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="single-time">0s</div>
                            <div class="text-white">Waktu</div>
                        </div>
                    </div>
                </div>

                <div class="core-type">
                    <h3 class="core-title">Multi-Core (4 Kasir)</h3>
                    <div class="checkout-area">
                        <div class="cashiers-container">
                            <div class="cashier text-white" >Kasir 1</div>
                            <div class="cashier text-white" >Kasir 2</div>
                            <div class="cashier text-white" >Kasir 3</div>
                            <div class="cashier text-white" >Kasir 4</div>
                        </div>
                        <div class="queue" id="multi-queue">
                            <!-- Pelanggan akan ditambahkan di sini -->
                        </div>
                    </div>
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-value" id="multi-customers">0</div>
                            <div class="text-white">Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="multi-time">0s</div>
                            <div class="text-white">Waktu</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="analogy-explanation reveal">
                <p class="text-white">Dalam analogi ini, setiap pelanggan mewakili sebuah tugas (task) yang perlu diproses oleh CPU. Single-core seperti memiliki hanya satu kasir yang harus melayani semua pelanggan secara berurutan, sementara multi-core memiliki beberapa kasir yang dapat melayani pelanggan secara bersamaan.</p>
            </div>
        </div>
    </div>
</section>