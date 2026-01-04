<section id="glossary" class="glossary-section">
    <div class="container">
        <h2 class="section-title reveal">Glosarium</h2>
        <div class="glossary-container">
            @foreach($glossary as $glossary)
            <div class="glossary-term reveal">
                <div class="term-title">
                    <span class="term-name">{{ $glossary->title }}</span>
                    <span class="term-toggle"><ion-icon name="chevron-down" class="chevron-icon"></ion-icon></span>
                </div>
                <div class="term-definition">
                    <p>{{ $glossary->explanation }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>