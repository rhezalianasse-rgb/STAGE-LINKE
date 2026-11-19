<section class="student-page-hero">
    <div class="student-page-hero-inner">
        <nav class="student-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ route('stagiaire.dashboard') }}" aria-label="Tableau de bord"><i class="fa-solid fa-house" aria-hidden="true"></i></a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <a href="{{ route('stagiaire.profil') }}">Profil</a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <span>{{ $breadcrumb }}</span>
        </nav>
        <div class="student-hero-copy">
            <p class="student-hero-kicker">STAGE LINK · VOTRE ESPACE</p>
            <h1>{{ $prefix }} <span>{{ $accent }}</span></h1>
            <p>{{ $description }}</p>
            <span class="student-hero-underline"></span>
        </div>
        <div class="student-hero-art" aria-hidden="true">
            <span class="student-hero-art-main"><i class="fa-solid {{ $icon }}"></i></span>
            <span class="student-hero-art-small student-hero-art-one"><i class="fa-solid fa-star"></i></span>
            <span class="student-hero-art-small student-hero-art-two"><i class="fa-solid fa-chart-line"></i></span>
            <span class="student-hero-art-small student-hero-art-three"><i class="fa-solid fa-gear"></i></span>
        </div>
    </div>
</section>
