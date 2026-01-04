<section id="quiz" class="quiz-invite-section reveal">
    <div class="container quiz-invite-wrapper">
        <h2 class="section-title reveal">Ikuti Quiz untuk mendapatkan hadiah Menarik!</h2> 
        <div class="quiz-invite-content">
            <div class="quiz-invite-image">
                <lottie-player src="{{ asset('anim/Developer.json') }}" 
                    background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay>
                </lottie-player>
            </div>

            
            <div id="quiz-form-container">
            @if($quiz_results) 
                <div class="quiz-result-card" style="text-align: center;">
                 <h2 class="quiz-title">Anda sudah melakukan quiz, Terimakasih</h2>
                    <p style="margin-bottom: 10px;">{{ $quiz_results->nama }}</p>
                    
                    <div class="score-circle" style="width: 100px; height: 100px; background: #6e8efb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 20px auto; color: white; font-size: 2.5rem; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        {{ $quiz_results->score }}
                    </div>
                    
                    <div style="display: inline-block; padding: 5px 15px; background: #e0f2f1; color: #00796b; border-radius: 20px; font-weight: bold;">
                        {{ $quiz_results->reward_status }}
                    </div>
                </div>
            @else
                <form action="/submit-quiz" method="POST" class="quiz-form">
                    @csrf
                    <h2 class="quiz-title">Isi form ini dengan benar dan ikuti quiznya</h2>
                    <div class="floating-group">
                        <input type="text" id="nama" name="nama" placeholder=" " required autocomplete="off">
                        <label for="nama">Nama Lengkap</label>
                    </div>
                    
                    <div class="floating-group">
                        <input type="email" id="email" name="email" placeholder=" " required autocomplete="off">
                        <label for="email">Alamat Email</label>
                    </div>

                    <div class="floating-group">
                        <select id="who-explain" name="who-explain" required>
                            <option value="" disabled selected hidden></option>
                            @foreach($teams as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        <label for="who-explain">Siapa yang menjelaskan?</label>
                    </div>

                    <button type="submit" class="btn quiz-btn">Mulai Quiz</button>
                </form>
            @endif
        </div>
        </div>
    </div>
</section>

<div id="quiz-popup" class="quiz-overlay" style="display: none;">
    <div class="quiz-modal">
        
        <div class="quiz-header">
            <div class="timer-container">
                <div class="timer-bar" id="timer-bar"></div>
                <span id="timer-text">10s</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <p id="question-counter">
                Soal 1 / <span id="total-questions"></span>
            </p>
        </div>

        <div id="quiz-content">
            <h3 id="question-text" class="question-text">Memuat pertanyaan...</h3>
            <div id="answer-options" class="answer-grid"></div>
        </div>

        <div id="quiz-result-view" class="quiz-result-container" style="display: none;">
            <div class="result-icon">🎁</div>
            <h2>Hasil Quiz Kamu</h2>
            <div class="score-circle">
                <span id="final-score">0</span>
            </div>
            <p id="score-status" class="score-status"></p>
            <p id="score-desc" class="score-desc"></p>
            <button class="btn" id="close-quiz-btn">Kembali ke Beranda</button>
        </div>

        </div>
</div>
<script>
    const rawQuizzesData = @json($quizzes);
</script>