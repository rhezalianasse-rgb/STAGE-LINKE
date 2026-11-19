@extends('layouts.app')

@section('body-class', 'home-page')
@section('title', 'Accueil')

@section('content')
<section class="nest-hero">
    <div class="nest-shell">
        <div class="nest-hero-card">
            <div class="nest-hero-image"></div>
            <div class="nest-hero-content">
                <div class="nest-pill"><i class="fa-solid fa-graduation-cap"></i> Formation <span>+</span> Stage <span>+</span> Avenir</div>
                <h1>Construisez Votre<br>Prochain <span>Chapitre</span></h1>
                <p>Trouvez une offre de stage adaptée à vos ambitions et lancez votre carrière avec les bonnes entreprises.</p>
            </div>

            <div class="nest-social-proof">
                <div class="nest-avatars">
                    <img src="https://i.pravatar.cc/80?img=12" alt="">
                    <img src="https://i.pravatar.cc/80?img=47" alt="">
                    <img src="https://i.pravatar.cc/80?img=32" alt="">
                </div>
                <div><strong>+{{ $homeStats['stagiaires'] }} stagiaires</strong><span>déjà accompagnés</span></div>
            </div>

            <form class="nest-search" action="{{ route('offres.index') }}" method="GET" role="search">
                <div class="nest-search-field nest-location">
                    <span class="nest-search-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <label for="home-location">
                        <input id="home-location" name="lieu" type="text" placeholder="Localisation" aria-label="Localisation">
                    </label>
                    <i class="fa-solid fa-chevron-down nest-chevron"></i>
                </div>
                <div class="nest-search-field">
                    <span class="nest-search-icon"><i class="fa-solid fa-building"></i></span>
                    <label for="home-domain">
                        <input id="home-domain" name="keyword" type="search" placeholder="Secteur" aria-label="Secteur">
                    </label>
                    <i class="fa-solid fa-chevron-down nest-chevron"></i>
                </div>
                <div class="nest-search-field">
                    <span class="nest-search-icon nest-money"><i class="fa-solid fa-calendar-days"></i></span>
                    <label for="home-type">
                        <input id="home-type" name="type" type="text" placeholder="Type de stage" aria-label="Type de stage">
                    </label>
                    <i class="fa-solid fa-chevron-down nest-chevron"></i>
                </div>
                <button type="submit" aria-label="Rechercher" title="Rechercher"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
</section>

<section class="nest-benefits" aria-label="Avantages de Stage Link">
    <div class="nest-shell nest-benefit-grid">
        <article><span><i class="fa-solid fa-building-circle-check"></i></span><div><strong>Entreprises vérifiées</strong><small>Des partenaires fiables</small></div></article>
        <article><span><i class="fa-solid fa-shield-halved"></i></span><div><strong>Candidatures sécurisées</strong><small>Vos données sont protégées</small></div></article>
        <article><span><i class="fa-solid fa-headset"></i></span><div><strong>Accompagnement 7j/7</strong><small>Une aide à chaque étape</small></div></article>
        <article><span><i class="fa-solid fa-wand-magic-sparkles"></i></span><div><strong>Correspondances pertinentes</strong><small>Des offres adaptées à vous</small></div></article>
    </div>
</section>

<section class="nest-about">
    <div class="nest-shell nest-about-grid">
        <div class="nest-about-copy">
            <p class="nest-kicker">POURQUOI CHOISIR STAGE LINK</p>
            <h2>Bien Plus Qu’une<br>Simple Offre</h2>
            <p class="nest-about-lead">Stage Link relie votre formation aux opportunités professionnelles les plus pertinentes.</p>
            <ul>
                <li><i class="fa-solid fa-circle-check"></i>Un large choix d’offres dans tous les secteurs</li>
                <li><i class="fa-solid fa-circle-check"></i>Des candidatures simples et rapides</li>
                <li><i class="fa-solid fa-circle-check"></i>Des recommandations personnalisées</li>
                <li><i class="fa-solid fa-circle-check"></i>Un suivi clair de chaque candidature</li>
            </ul>
            <a href="{{ route('register') }}" class="nest-primary">Créer mon profil <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="nest-about-photo">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=85" alt="Étudiants collaborant autour d'un projet">
            <div class="nest-experience"><strong>{{ $homeStats['offres'] }}+</strong><span>offres disponibles</span></div>
        </div>
    </div>
</section>

<section class="nest-featured">
    <div class="nest-shell">
        <div class="nest-section-heading">
            <h2>Offres Recommandées</h2>
            <a href="{{ route('offres.index') }}">Voir toutes les offres <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="nest-property-grid">
            <article class="nest-property-card">
                <div class="nest-property-photo">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=85" alt="Développement informatique">
                    <span class="nest-badge">Populaire</span>
                    <button type="button" aria-label="Ajouter aux favoris"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="nest-property-body">
                    <h3>Développeur Full Stack</h3>
                    <p><i class="fa-solid fa-location-dot"></i> Casablanca, Maroc</p>
                    <div class="nest-property-meta"><span><i class="fa-solid fa-code"></i> Informatique</span><span><i class="fa-solid fa-clock"></i> PFE</span></div>
                    <div class="nest-property-footer"><strong>4 mois</strong><a href="{{ route('offres.index') }}">Voir l’offre</a></div>
                </div>
            </article>
            <article class="nest-property-card">
                <div class="nest-property-photo">
                    <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=85" alt="Marketing en entreprise">
                    <span class="nest-badge nest-badge-green">Nouveau</span>
                    <button type="button" aria-label="Ajouter aux favoris"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="nest-property-body">
                    <h3>Assistant Marketing Digital</h3>
                    <p><i class="fa-solid fa-location-dot"></i> Rabat, Maroc</p>
                    <div class="nest-property-meta"><span><i class="fa-solid fa-bullhorn"></i> Marketing</span><span><i class="fa-solid fa-clock"></i> 3 mois</span></div>
                    <div class="nest-property-footer"><strong>Stage PFE</strong><a href="{{ route('offres.index') }}">Voir l’offre</a></div>
                </div>
            </article>
            <article class="nest-property-card">
                <div class="nest-property-photo">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=85" alt="Gestion de projet">
                    <span class="nest-badge nest-badge-red">Urgent</span>
                    <button type="button" aria-label="Ajouter aux favoris"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="nest-property-body">
                    <h3>Gestionnaire de Projet</h3>
                    <p><i class="fa-solid fa-location-dot"></i> Tanger, Maroc</p>
                    <div class="nest-property-meta"><span><i class="fa-solid fa-chart-line"></i> Management</span><span><i class="fa-solid fa-clock"></i> 6 mois</span></div>
                    <div class="nest-property-footer"><strong>Temps plein</strong><a href="{{ route('offres.index') }}">Voir l’offre</a></div>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="nest-steps">
    <div class="nest-shell">
        <div class="nest-steps-heading">
            <p class="nest-kicker">COMMENT ÇA MARCHE</p>
            <h2>Votre Stage en 3 Étapes</h2>
        </div>
        <div class="nest-steps-grid">
            <article><div class="nest-step-icon"><i class="fa-solid fa-magnifying-glass"></i></div><span>01</span><h3>Explorez les offres</h3><p>Filtrez les opportunités selon vos compétences et vos envies.</p></article>
            <article><div class="nest-step-icon"><i class="fa-solid fa-user-plus"></i></div><span>02</span><h3>Créez votre profil</h3><p>Présentez votre parcours, vos compétences et votre motivation.</p></article>
            <article><div class="nest-step-icon"><i class="fa-solid fa-paper-plane"></i></div><span>03</span><h3>Postulez simplement</h3><p>Candidature rapide et suivi de vos réponses depuis votre espace.</p></article>
        </div>
    </div>
</section>

<section class="nest-testimonials">
    <div class="nest-shell nest-testimonial-grid">
        <article>
            <div class="nest-stars">★★★★★</div>
            <p>« J’ai trouvé mon stage PFE en moins d’une semaine. L’expérience a été simple et motivante. »</p>
            <div class="nest-person"><img src="https://i.pravatar.cc/80?img=47" alt=""><div><strong>Sarah Benali</strong><span>Développeuse stagiaire</span></div></div>
        </article>
        <article>
            <div class="nest-stars">★★★★★</div>
            <p>« Une plateforme claire qui m’a permis de découvrir plusieurs entreprises adaptées à mon profil. »</p>
            <div class="nest-person"><img src="https://i.pravatar.cc/80?img=12" alt=""><div><strong>Yassine Amrani</strong><span>Stagiaire informatique</span></div></div>
        </article>
        <article>
            <div class="nest-stars">★★★★★</div>
            <p>« Je peux suivre toutes mes candidatures au même endroit et recevoir rapidement des réponses. »</p>
            <div class="nest-person"><img src="https://i.pravatar.cc/80?img=32" alt=""><div><strong>Imane Alaoui</strong><span>Étudiante marketing</span></div></div>
        </article>
    </div>
</section>

<section class="nest-final-cta">
    <div class="nest-shell">
        <div class="nest-final-card">
            <div class="nest-final-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <div><h2>Prêt à trouver votre prochain stage ?</h2><p>Rejoignez des milliers de stagiaires qui avancent avec Stage Link.</p></div>
            <a href="{{ route('register') }}">Commencer <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    body.home-page { --nest-navy:#171b56; --nest-blue:#3f50c6; --nest-lilac:#eef0ff; --nest-text:#17182f; --nest-muted:#6d7285; background:#fff; font-family:'DM Sans',sans-serif; }
    body.home-page h1,body.home-page h2,body.home-page h3 { font-family:'Manrope',sans-serif; letter-spacing:-.02em; }
    body.home-page .navbar { height:76px; background:#fff; border:0; box-shadow:none; }
    body.home-page .navbar .container { max-width:1180px; }
    body.home-page .navbar-brand img { height:48px!important; }
    body.home-page .navbar .nav-link { color:#30344a!important; font-size:.92rem; }
    body.home-page .navbar .nav-link:hover { color:var(--nest-blue)!important; }
    body.home-page .navbar .btn-primary { background:var(--nest-navy); border-color:var(--nest-navy); }
    body.home-page .navbar .btn-outline-primary { color:var(--nest-navy); border-color:#dddff0; }
    html[data-theme="dark"] body.home-page .navbar { background:#fff; border:0; }
    html[data-theme="dark"] body.home-page .navbar .nav-link { color:#30344a!important; }
    html[data-theme="dark"] body.home-page main { background:#fff; }
    html[data-theme="dark"] body.home-page .nest-benefit-grid,
    html[data-theme="dark"] body.home-page .nest-about,
    html[data-theme="dark"] body.home-page .nest-featured,
    html[data-theme="dark"] body.home-page .nest-steps,
    html[data-theme="dark"] body.home-page .nest-testimonials,
    html[data-theme="dark"] body.home-page .nest-final-cta { background:#fff; }
    body.home-page footer { display:none; }
    body.home-page main { background:#fff; }
    .nest-shell { width:min(1180px,calc(100% - 48px)); margin-inline:auto; }
    .nest-hero { padding:20px 0 0; }
    .nest-hero-card { position:relative; min-height:445px; overflow:visible; border-radius:20px; background:#d8dbea; }
    .nest-hero-image { position:absolute; inset:0; border-radius:inherit; background:linear-gradient(90deg,rgba(34,38,91,.62) 0%,rgba(51,55,122,.32) 42%,rgba(51,55,122,.08) 75%), url('{{ asset('images/stg2.png') }}') center/cover no-repeat; }
    .nest-hero-content { position:absolute; z-index:2; top:70px; left:54px; max-width:520px; color:#fff; }
    .nest-pill { display:inline-flex; align-items:center; gap:8px; margin-bottom:22px; padding:8px 13px; border-radius:30px; background:rgba(255,255,255,.18); font-size:.78rem; font-weight:700; backdrop-filter:blur(8px); }
    .nest-pill i { color:#fff; }
    .nest-pill span { color:#bfc5ff; }
    .nest-hero h1 { margin:0; color:#fff; font-size:48px; line-height:1.08; }
    .nest-hero h1 span { color:#aeb7ff; }
    .nest-hero-content>p { max-width:430px; margin:18px 0 0; color:#eff1ff; line-height:1.65; }
    .nest-social-proof { position:absolute; z-index:3; top:26px; right:28px; display:flex; align-items:center; gap:10px; padding:8px 12px 8px 8px; border-radius:30px; background:rgba(255,255,255,.94); }
    .nest-avatars { display:flex; }
    .nest-avatars img { width:34px; height:34px; margin-right:-8px; border:2px solid #fff; border-radius:50%; object-fit:cover; }
    .nest-social-proof>div:last-child { display:flex; flex-direction:column; padding-left:4px; }
    .nest-social-proof strong { color:#24284b; font-size:.72rem; }
    .nest-social-proof span { color:#777d91; font-size:.66rem; }
    .nest-search { position:absolute; z-index:4; right:44px; bottom:-37px; left:44px; display:grid; grid-template-columns:1.18fr 1fr 1fr 168px; align-items:center; height:82px; padding:8px; border:0; border-radius:18px; background:#fff; box-shadow:0 12px 30px rgba(35,55,150,.22); }
    .nest-search-field { display:flex; align-items:center; height:56px; min-width:0; padding:0 17px; overflow:hidden; border-right:1px solid #e6eaf5; }
    .nest-search-icon { display:grid; width:48px; height:48px; flex:0 0 48px; margin-right:16px; place-items:center; border-radius:50%; color:#4059d4; background:#eef0ff; font-size:1.05rem; }
    .nest-search-field label { min-width:0; flex:1; }
    .nest-search-field input { width:100%; height:40px; padding:0; border:0; outline:0; color:#63749a; background:transparent; font-size:.88rem; font-weight:600; line-height:40px; }
    .nest-search-field input::placeholder { color:#7181a4; opacity:1; }
    .nest-chevron { margin-left:12px; color:#6379ad; font-size:.75rem; }
    .nest-search button { display:grid; width:100%; height:56px; place-items:center; border:0; border-radius:14px; color:#fff; background:#3048ed; font-size:1.35rem; box-shadow:0 5px 12px rgba(48,72,237,.18); }
    .nest-search button:hover { background:#243bd4; }
    .nest-benefits { padding:82px 0 22px; }
    .nest-benefit-grid { display:grid; grid-template-columns:repeat(4,1fr); }
    .nest-benefit-grid article { display:flex; align-items:center; gap:12px; min-height:58px; padding:0 18px; border-right:1px solid #edf0f5; }
    .nest-benefit-grid article:last-child { border:0; }
    .nest-benefit-grid article>span { display:grid; width:43px; height:43px; flex:0 0 43px; place-items:center; border-radius:10px; color:#4a57c4; background:#eef0ff; }
    .nest-benefit-grid strong,.nest-benefit-grid small { display:block; }
    .nest-benefit-grid strong { color:#252840; font-size:.82rem; }
    .nest-benefit-grid small { margin-top:3px; color:#898d9d; font-size:.72rem; }
    .nest-about { padding:60px 0; }
    .nest-about-grid { display:grid; grid-template-columns:.8fr 1.2fr; align-items:center; gap:74px; }
    .nest-kicker { margin:0 0 10px; color:#5867d4!important; font-size:.72rem; font-weight:800; letter-spacing:.12em; }
    .nest-about h2,.nest-steps h2 { margin:0; color:#17182f; font-size:38px; line-height:1.15; }
    .nest-about-lead { max-width:430px; margin:16px 0 18px; color:#696d7d; line-height:1.7; }
    .nest-about ul { padding:0; margin:0 0 26px; list-style:none; }
    .nest-about li { margin:9px 0; color:#44485a; font-size:.88rem; }
    .nest-about li i { margin-right:9px; color:#5965ce; }
    .nest-primary { display:inline-flex; align-items:center; gap:12px; padding:13px 18px; border-radius:8px; color:#fff!important; background:#303c9d; text-decoration:none; font-weight:700; }
    .nest-about-photo { position:relative; }
    .nest-about-photo img { width:100%; height:380px; border-radius:18px; object-fit:cover; }
    .nest-experience { position:absolute; right:-18px; bottom:-20px; display:flex; flex-direction:column; min-width:148px; padding:18px; border:8px solid #fff; border-radius:14px; background:#eef0ff; }
    .nest-experience strong { color:#3d4bb4; font-size:1.35rem; }
    .nest-experience span { color:#777b8d; font-size:.72rem; }
    .nest-featured { padding:58px 0 68px; background:#fbfbfd; }
    .nest-section-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
    .nest-section-heading h2 { margin:0; color:#17182f; font-size:26px; }
    .nest-section-heading a { color:#4f5bc1; text-decoration:none; font-size:.78rem; font-weight:700; }
    .nest-property-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    .nest-property-card { overflow:hidden; border:1px solid #e9ebf2; border-radius:14px; background:#fff; box-shadow:0 8px 24px rgba(34,39,79,.06); }
    .nest-property-photo { position:relative; height:210px; }
    .nest-property-photo img { width:100%; height:100%; object-fit:cover; }
    .nest-property-photo button { position:absolute; top:12px; right:12px; width:36px; height:36px; border:0; border-radius:50%; color:#333750; background:#fff; }
    .nest-badge { position:absolute; top:12px; left:12px; padding:6px 9px; border-radius:7px; color:#4c55bd; background:#eef0ff; font-size:.68rem; font-weight:800; }
    .nest-badge-green { color:#168466; background:#e7f7f1; }
    .nest-badge-red { color:#c44a52; background:#fdebec; }
    .nest-property-body { padding:15px 16px 12px; }
    .nest-property-body h3 { margin:0 0 7px; color:#242640; font-size:.98rem; }
    .nest-property-body p { margin:0 0 13px; color:#7b8092; font-size:.76rem; }
    .nest-property-body p i { color:#5965c7; margin-right:5px; }
    .nest-property-meta { display:flex; gap:18px; padding-bottom:12px; border-bottom:1px solid #edf0f5; color:#686d7d; font-size:.72rem; }
    .nest-property-meta i { color:#777fd1; margin-right:5px; }
    .nest-property-footer { display:flex; align-items:center; justify-content:space-between; padding-top:11px; }
    .nest-property-footer strong { color:#303770; font-size:.9rem; }
    .nest-property-footer a { padding:8px 12px; border-radius:7px; color:#fff!important; background:#303b9d; text-decoration:none; font-size:.72rem; font-weight:700; }
    .nest-steps { padding:64px 0 70px; background:#fff; }
    .nest-steps-heading { text-align:center; }
    .nest-steps-grid { position:relative; display:grid; grid-template-columns:repeat(3,1fr); gap:25px; margin-top:34px; }
    .nest-steps-grid article { position:relative; min-height:190px; padding:26px 34px; text-align:center; border:1px solid #edf0f5; border-radius:14px; background:#fbfbfd; }
    .nest-steps-grid article>span { position:absolute; top:15px; right:18px; color:#c6c8d4; font-size:.68rem; font-weight:800; }
    .nest-step-icon { display:grid; width:54px; height:54px; margin:0 auto 12px; place-items:center; border:7px solid #f1f2ff; border-radius:50%; color:#fff; background:#5866ce; box-shadow:0 5px 15px rgba(88,102,206,.18); }
    .nest-steps-grid h3 { margin:8px 0; color:#242640; font-size:.96rem; }
    .nest-steps-grid p { max-width:260px; margin:auto; color:#7d8191; font-size:.78rem; line-height:1.55; }
    .nest-testimonials { padding:45px 0; background:#fafafd; }
    .nest-testimonial-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
    .nest-testimonial-grid article { padding:20px; border:1px solid #eceef5; border-radius:12px; background:#fff; }
    .nest-stars { color:#8a91dc; font-size:.75rem; letter-spacing:2px; }
    .nest-testimonial-grid article>p { min-height:68px; margin:10px 0 14px; color:#55596b; font-size:.76rem; line-height:1.55; }
    .nest-person { display:flex; align-items:center; gap:9px; }
    .nest-person img { width:38px; height:38px; border-radius:50%; object-fit:cover; }
    .nest-person strong,.nest-person span { display:block; }
    .nest-person strong { color:#303348; font-size:.76rem; }
    .nest-person span { color:#969aaa; font-size:.68rem; }
    .nest-final-cta { padding:32px 0 38px; background:#fff; }
    .nest-final-card { display:grid; grid-template-columns:58px 1fr auto; align-items:center; gap:18px; min-height:130px; padding:24px 36px; border-radius:16px; color:#fff; background:linear-gradient(110deg,#29368e,#3e4cb1 70%,#7b67ca); }
    .nest-final-icon { display:grid; width:54px; height:54px; place-items:center; border:1px solid rgba(255,255,255,.22); border-radius:12px; background:rgba(255,255,255,.1); }
    .nest-final-card h2 { margin:0 0 5px; color:#fff; font-size:20px; }
    .nest-final-card p { margin:0; color:#dfe3ff; font-size:.78rem; }
    .nest-final-card>a { padding:12px 18px; border-radius:8px; color:#30376f!important; background:#fff; text-decoration:none; font-size:.78rem; font-weight:800; }
    @media(max-width:991px) {
        .nest-search { grid-template-columns:1fr 1fr 140px; }
        .nest-search-field:nth-child(3) { display:none; }
        .nest-benefit-grid { grid-template-columns:repeat(2,1fr); row-gap:12px; }
        .nest-benefit-grid article:nth-child(2) { border:0; }
        .nest-about-grid { gap:35px; }
        .nest-property-grid { grid-template-columns:repeat(2,1fr); }
        .nest-property-card:last-child { display:none; }
    }
    @media(max-width:575px) {
        body.home-page .navbar { height:66px; }
        .nest-shell { width:min(100% - 28px,1180px); }
        .nest-hero-card { min-height:520px; border-radius:14px; }
        .nest-hero-image { background:linear-gradient(90deg,rgba(34,38,91,.72),rgba(51,55,122,.42)),url('{{ asset('images/stg2.png') }}') center/cover no-repeat; }
        .nest-hero-content { top:54px; left:24px; max-width:calc(100% - 48px); }
        .nest-hero h1 { font-size:36px; }
        .nest-social-proof { display:none; }
        .nest-search { right:12px; bottom:-35px; left:12px; grid-template-columns:1fr 58px; gap:0; height:76px; padding:7px; }
        .nest-search-field { height:54px; padding:0 8px; border-right:0; }
        .nest-search-field:nth-child(2),.nest-search-field:nth-child(3) { display:none; }
        .nest-search-icon { width:42px; height:42px; flex-basis:42px; margin-right:10px; }
        .nest-search button { height:54px; border-radius:12px; }
        .nest-benefits { padding-top:64px; }
        .nest-benefit-grid { grid-template-columns:1fr 1fr; }
        .nest-benefit-grid article { padding:8px 4px; }
        .nest-benefit-grid article:nth-child(2) { border-right:0; }
        .nest-benefit-grid article>span { width:38px; height:38px; flex-basis:38px; }
        .nest-benefit-grid strong { font-size:.72rem; }
        .nest-about { padding:38px 0; }
        .nest-about-grid { grid-template-columns:1fr; }
        .nest-about h2,.nest-steps h2 { font-size:30px; }
        .nest-about-photo img { height:280px; }
        .nest-experience { right:8px; }
        .nest-featured { padding:42px 0; }
        .nest-property-grid { grid-template-columns:1fr; }
        .nest-property-card:nth-child(2) { display:none; }
        .nest-steps-grid { grid-template-columns:1fr; }
        .nest-testimonial-grid { grid-template-columns:1fr; }
        .nest-testimonial-grid article:nth-child(n+2) { display:none; }
        .nest-final-card { grid-template-columns:46px 1fr; padding:20px; }
        .nest-final-card>a { grid-column:2; justify-self:start; }
        .nest-final-card h2 { font-size:17px; }
    }
</style>
@endsection
