<section id="team" class="team-section">
    <div class="container">
        <h2 class="section-title reveal">Team Corewar</h2>

        <div class="team-container">
            <div class="swiper team-swiper">
                <div class="swiper-wrapper">

                    @foreach($teams as $team)
                    <div class="swiper-slide">
                        <div class="team-member">
                            <div class="member-image-wrapper">
                                <img
                                    src="{{ $team->photo ? asset($team->photo) : asset('img/default-user.png') }}"
                                    alt="{{ $team->name }}"
                                    class="member-image"
                                >
                            </div>
                            <h3>{{ $team->name }}</h3>
                            <p class="member-role">{{ $team->role }}</p>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="slide-navigation-area prev-area" id="prevArea"></div>
                <div class="slide-navigation-area next-area" id="nextArea"></div>
            </div>

            <div class="analogy-explanation reveal">
                <p>Tim kami bekerja secara kolaboratif dengan pembagian peran yang jelas. Setiap anggota berkontribusi sesuai keahliannya, sehingga proses kerja berjalan efisien dan hasil yang dihasilkan tetap optimal.</p>
            </div>
            
        </div>
    </div>
</section>
