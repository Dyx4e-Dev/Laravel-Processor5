<section class="hero">
    <div class="container"">
        <div class="hero-grid">
            <div class="hero-content">
                <h1 class="reveal">
                    <span id="typed"></span><span class="cursor"></span>
                </h1>
                <p class="reveal">{{ $webSetting->subtitle }}</p>
                <a href="#analogy" class="btn reveal">Mulai Eksplorasi</a>
            </div>

            <div class="hero-animation reveal">
                <lottie-player src="{{ asset('anim/CMS computer animation.json') }}" 
                    background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay>
                </lottie-player>

            </div>
        </div>
    </div>
</section>