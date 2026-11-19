@extends('layouts.app')
@php
    $isStagiaire = auth()->check() && auth()->user()->role === 'stagiaire';
@endphp
@section('title', 'Offres de Stage')
@section('body-class', $isStagiaire ? 'offers-page offers-student-page' : 'offers-page')
@section('content')
@if($isStagiaire)
<div class="student-dashboard offers-dashboard-shell">
    @include('stagiaire.partials.sidebar')
    <div class="offers-main">
        <header class="offers-topbar">
            <nav class="offers-topnav" aria-label="Navigation du stagiaire">
                <a href="{{ route('stagiaire.dashboard') }}">Dashboard</a>
                <a class="active" href="{{ route('offres.index') }}" aria-current="page">Offres</a>
                <a href="{{ route('stagiaire.candidatures') }}">Mes candidatures</a>
                <a href="{{ route('stagiaire.favoris') }}">Mes favoris</a>
            </nav>
            <div class="offers-top-actions">
                <button class="offers-icon-button" type="button" data-theme-toggle aria-label="Activer le mode sombre" title="Activer le mode sombre" aria-pressed="false">
                    <i class="fa-solid fa-moon" data-theme-icon aria-hidden="true"></i>
                </button>
                <a class="offers-icon-button offers-notification" href="{{ route('notifications.index') }}" aria-label="Notifications">
                    <i class="fa-regular fa-bell" aria-hidden="true"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span>{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <a class="offers-user" href="{{ route('stagiaire.profil') }}">
                    @if(auth()->user()->stagiaire?->photo_path)
                        <img src="{{ route('profile-photos.show', ['filename' => basename(auth()->user()->stagiaire->photo_path)]) }}" alt="Photo de {{ auth()->user()->prenom ?? 'profil' }}">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->prenom ?? 'S', 0, 1)) }}</span>
                    @endif
                    <strong>{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</strong>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </a>
            </div>
        </header>
@endif

<div class="offers-page-content {{ $isStagiaire ? 'offers-page-student-content' : 'offers-page-public-content' }}">
    <section class="offers-hero">
        <div class="offers-hero-copy">
            <p class="offers-kicker">STAGE LINK · OPPORTUNITÉS</p>
            <h1>Trouver un <span>stage</span></h1>
            <p>Des offres adaptées à votre formation et à votre projet professionnel.</p>
            @if(auth()->check() && auth()->user()->role === 'entreprise')
                <a href="{{ route('entreprise.offres.create') }}" class="offers-publish">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Publier une offre
                </a>
            @endif
        </div>
        <i class="fa-solid fa-graduation-cap offers-hero-cap" aria-hidden="true"></i>
    </section>

    <div class="offers-content-inner">
        <section class="offers-filter-card" aria-label="Filtres de recherche">
            <form action="{{ route('offres.index') }}" method="GET" class="offers-filter-form">
                <div class="offers-filter-field">
                    <label for="keyword"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Mot-clé</label>
                    <input type="text" id="keyword" name="keyword" placeholder="Rechercher..." value="{{ request('keyword') }}">
                </div>
                <div class="offers-filter-field">
                    <label for="type_stage"><i class="fa-solid fa-briefcase" aria-hidden="true"></i> Type de stage</label>
                    <select id="type_stage" name="type_stage">
                        <option value="">Tous les types</option>
                        <option value="PFE" {{ request('type_stage') == 'PFE' ? 'selected' : '' }}>PFE</option>
                        <option value="Stage d'été" {{ request('type_stage') == "Stage d'été" ? 'selected' : '' }}>Stage d'été</option>
                        <option value="Stage d'observation" {{ request('type_stage') == "Stage d'observation" ? 'selected' : '' }}>Stage d'observation</option>
                        <option value="Stage pré-emploi" {{ request('type_stage') == 'Stage pré-emploi' ? 'selected' : '' }}>Stage pré-emploi</option>
                    </select>
                </div>
                <div class="offers-filter-field">
                    <label for="localisation"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Localisation</label>
                    <input type="text" id="localisation" name="localisation" placeholder="Ville..." value="{{ request('localisation') }}">
                </div>
                <div class="offers-filter-field">
                    <label for="duree"><i class="fa-regular fa-clock" aria-hidden="true"></i> Durée</label>
                    <select id="duree" name="duree">
                        <option value="">Toutes</option>
                        <option value="1-2" {{ request('duree') == '1-2' ? 'selected' : '' }}>1-2 mois</option>
                        <option value="3-4" {{ request('duree') == '3-4' ? 'selected' : '' }}>3-4 mois</option>
                        <option value="5-6" {{ request('duree') == '5-6' ? 'selected' : '' }}>5-6 mois</option>
                        <option value="+6" {{ request('duree') == '+6' ? 'selected' : '' }}>+6 mois</option>
                    </select>
                </div>
                <div class="offers-filter-field">
                    <label for="specialite_id"><i class="fa-solid fa-grip" aria-hidden="true"></i> Domaine</label>
                    <select id="specialite_id" name="specialite_id">
                        <option value="">Tous les domaines</option>
                        @foreach($specialites as $specialite)
                            <option value="{{ $specialite->id }}" {{ request('specialite_id') == $specialite->id ? 'selected' : '' }}>{{ $specialite->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="offers-search-submit">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><span>Rechercher</span>
                </button>
            </form>
        </section>

        <div class="offers-results-heading">
            <div>
                <h2>Offres de stage</h2>
                <p>{{ $offres->total() }} opportunité{{ $offres->total() > 1 ? 's' : '' }} disponible{{ $offres->total() > 1 ? 's' : '' }}</p>
            </div>
        </div>

        <div class="offers-grid">
            @forelse($offres ?? [] as $offre)
                @php
                    $isFavorite = in_array($offre->id, $favorisIds ?? []);
                    $matchRate = null;
                    if ($isStagiaire && $offre->competences_count > 0) {
                        $matchRate = round(count(array_intersect($stagiaireCompetencesIds, $offre->competences->modelKeys())) / $offre->competences_count * 100);
                    }
                @endphp
                <article class="offer-card">
                    <div class="offer-card-top">
                        <div class="offer-company">
                            @if($offre->entreprise && $offre->entreprise->logo_path)
                                <img src="{{ asset('storage/' . $offre->entreprise->logo_path) }}" alt="" class="offer-company-logo">
                            @else
                                <span class="offer-company-logo offer-company-placeholder"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                            @endif
                            <div class="offer-title-wrap">
                                <h3>{{ $offre->titre }}</h3>
                                <p>{{ $offre->entreprise->nom_entreprise ?? 'Entreprise inconnue' }}</p>
                            </div>
                        </div>
                        @if($isStagiaire)
                            <div class="offer-card-actions">
                                <form action="{{ route('stagiaire.offres.favori', $offre->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="offer-favorite {{ $isFavorite ? 'is-favorite' : '' }}" title="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}" aria-label="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                        <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart" aria-hidden="true"></i>
                                    </button>
                                </form>
                                @if($matchRate !== null)
                                    <span class="offer-match"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ $matchRate }}% Match</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <p class="offer-description">{{ Str::limit($offre->description, 115) }}</p>

                    <div class="offer-tags">
                        <span class="offer-tag offer-tag-type"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> {{ $offre->type_stage }}</span>
                        <span class="offer-tag offer-tag-duration"><i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $offre->duree }} mois</span>
                    </div>

                    <div class="offer-card-footer">
                        <p><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $offre->localisation }}</p>
                        <a href="{{ route('offres.show', $offre->id) }}" aria-label="Voir l'offre {{ $offre->titre }}">
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                    <p class="offer-published">Publié le {{ $offre->created_at->format('d/m/Y') }}</p>
                    @if($offre->date_expiration)
                        <p class="offer-expiration">Candidatures jusqu'au {{ $offre->date_expiration->format('d/m/Y') }}</p>
                    @endif
                </article>
            @empty
                <div class="offers-empty">
                    <span><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></span>
                    <h3>Aucune offre trouvée</h3>
                    <p>Essayez de modifier vos filtres pour découvrir d'autres opportunités.</p>
                    <a href="{{ route('offres.index') }}">Effacer les filtres</a>
                </div>
            @endforelse
        </div>

        @if(isset($offres) && method_exists($offres, 'links') && $offres->lastPage() > 1)
            <nav class="offers-pagination" aria-label="Pagination des offres">
                <div class="offers-pagination-pages">
                    @foreach($offres->withQueryString()->getUrlRange(1, $offres->lastPage()) as $page => $url)
                        @if($page == $offres->currentPage())
                            <span class="offers-pagination-page is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="offers-pagination-page" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
            </nav>
        @endif
    </div>
</div>

@if($isStagiaire)
    </div>
</div>
@endif
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    body.offers-page { --offers-navy:#122d77; --offers-blue:#2451ee; --offers-ink:#17295f; --offers-muted:#65759f; --offers-line:#e4e9fb; --offers-bg:#f3f5ff; background:var(--offers-bg); color:var(--offers-ink); font-family:'DM Sans',sans-serif; }
    body.offers-page h1,body.offers-page h2,body.offers-page h3 { font-family:'Manrope',sans-serif; letter-spacing:-.025em; }
    body.offers-page .offers-page-content { min-width:0; }
    body.offers-student-page .navbar,body.offers-student-page footer { display:none; }
    body.offers-student-page main { display:block; min-height:100vh; padding:0; }
    body.offers-page .offers-page-public-content { max-width:1420px; margin:0 auto; padding:24px clamp(16px,3vw,42px) 42px; }
    body.offers-page .offers-hero { position:relative; display:flex; min-height:224px; align-items:center; overflow:hidden; padding:30px clamp(22px,3vw,42px); border-radius:18px; background-color:#e4ebff; background-image:linear-gradient(90deg,#f3f5ff 0%,rgba(243,245,255,.97) 34%,rgba(243,245,255,.48) 58%,rgba(243,245,255,.08) 100%),url('{{ asset('images/stg2.png') }}'); background-repeat:no-repeat; background-position:center,right 47%; background-size:cover,cover; }
    body.offers-page .offers-page-public-content .offers-hero { margin-bottom:0; }
    body.offers-page .offers-hero-copy { position:relative; z-index:1; }
    body.offers-page .offers-kicker { margin:0 0 8px; color:#215cf1; font-size:.75rem; font-weight:800; letter-spacing:.04em; }
    body.offers-page .offers-hero h1 { margin:0; color:#142d70; font-size:clamp(2.2rem,4vw,3rem); font-weight:800; line-height:1.08; }
    body.offers-page .offers-hero h1 span { color:#1b58ef; }
    body.offers-page .offers-hero-copy>p:not(.offers-kicker) { margin:10px 0 0; color:#536797; font-size:.98rem; }
    body.offers-page .offers-hero-cap { position:absolute; top:25px; right:22%; color:rgba(43,101,239,.38); font-size:3.6rem; transform:rotate(-15deg); }
    body.offers-page .offers-publish { display:inline-flex; align-items:center; gap:8px; margin-top:14px; padding:9px 14px; border-radius:9px; color:#fff; background:#2054e8; font-size:.8rem; font-weight:700; text-decoration:none; }
    body.offers-page .offers-content-inner { position:relative; z-index:2; }
    body.offers-page .offers-page-public-content .offers-filter-card { margin:-24px 0 30px; }
    body.offers-page .offers-filter-card { padding:15px 16px; border:1px solid #e6ebff; border-radius:19px; background:rgba(255,255,255,.97); box-shadow:0 14px 30px rgba(37,76,181,.12); }
    body.offers-page .offers-filter-form { display:grid; grid-template-columns:1.05fr 1.05fr .95fr .8fr 1.2fr 150px; align-items:end; gap:11px; }
    body.offers-page .offers-filter-field { min-width:0; }
    body.offers-page .offers-filter-field label { display:flex; align-items:center; gap:8px; margin:0 0 7px; color:#183373; font-size:.76rem; font-weight:700; white-space:nowrap; }
    body.offers-page .offers-filter-field label i { color:#1d5af2; font-size:.85rem; }
    body.offers-page .offers-filter-field input,body.offers-page .offers-filter-field select { width:100%; height:42px; min-width:0; padding:0 12px; border:1px solid #dfe6fb; border-radius:9px; outline:0; color:#3e5385; background:#fff; font:500 .78rem 'DM Sans',sans-serif; }
    body.offers-page .offers-filter-field select { padding-right:25px; }
    body.offers-page .offers-filter-field input:focus,body.offers-page .offers-filter-field select:focus { border-color:#6f91fb; box-shadow:0 0 0 3px rgba(51,97,239,.12); }
    body.offers-page .offers-search-submit { display:flex; min-height:44px; align-items:center; justify-content:center; gap:9px; padding:0 14px; border:0; border-radius:11px; color:#fff; background:linear-gradient(100deg,#1456f5,#3976ff); box-shadow:0 7px 16px rgba(34,83,234,.2); font:.78rem 'DM Sans',sans-serif; font-weight:800; white-space:nowrap; }
    body.offers-page .offers-search-submit:hover { filter:brightness(1.07); }
    body.offers-page .offers-results-heading { display:flex; align-items:end; justify-content:space-between; margin:0 4px 14px; }
    body.offers-page .offers-results-heading h2 { margin:0; color:#173273; font-size:1.25rem; font-weight:800; }
    body.offers-page .offers-results-heading p { margin:4px 0 0; color:#7180a6; font-size:.78rem; }
    body.offers-page .offers-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:15px; }
    body.offers-page .offer-card { display:flex; min-width:0; min-height:240px; flex-direction:column; padding:18px; border:1px solid #e4e9f9; border-radius:16px; background:rgba(255,255,255,.94); box-shadow:0 8px 18px rgba(30,57,139,.08); transition:transform .18s ease,box-shadow .18s ease; }
    body.offers-page .offer-card:hover { box-shadow:0 13px 24px rgba(30,57,139,.13); transform:translateY(-2px); }
    body.offers-page .offer-card-top { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
    body.offers-page .offer-company { display:flex; min-width:0; align-items:center; gap:12px; }
    body.offers-page .offer-company-logo { width:55px; height:55px; flex:0 0 55px; border-radius:14px; object-fit:cover; }
    body.offers-page .offer-company-placeholder { display:grid; place-items:center; color:#3c70f2; background:#e9efff; font-size:1.25rem; }
    body.offers-page .offer-title-wrap { min-width:0; }
    body.offers-page .offer-title-wrap h3 { display:-webkit-box; overflow:hidden; margin:0 0 5px; color:#172f72; font-size:.93rem; font-weight:800; line-height:1.3; -webkit-box-orient:vertical; -webkit-line-clamp:2; }
    body.offers-page .offer-title-wrap p { overflow:hidden; margin:0; color:#6b7ba4; font-size:.72rem; text-overflow:ellipsis; white-space:nowrap; }
    body.offers-page .offer-card-actions { display:flex; min-width:42px; align-items:flex-end; flex-direction:column; gap:7px; }
    body.offers-page .offer-card-actions form { margin:0; }
    body.offers-page .offer-favorite { display:grid; width:30px; height:30px; place-items:center; border:0; color:#7182ad; background:transparent; font-size:1.08rem; }
    body.offers-page .offer-favorite:hover,body.offers-page .offer-favorite.is-favorite { color:#e54865; }
    body.offers-page .offer-match { display:inline-flex; align-items:center; gap:4px; padding:5px 7px; border-radius:7px; color:#07814d; background:#daf6e9; font-size:.61rem; font-weight:800; white-space:nowrap; }
    body.offers-page .offer-description { display:-webkit-box; overflow:hidden; min-height:38px; margin:13px 0 10px; color:#51638e; font-size:.78rem; line-height:1.45; -webkit-box-orient:vertical; -webkit-line-clamp:2; }
    body.offers-page .offer-tags { display:flex; flex-wrap:wrap; gap:7px; margin-top:auto; }
    body.offers-page .offer-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 9px; border-radius:7px; font-size:.69rem; font-weight:700; }
    body.offers-page .offer-tag-type { color:#1765c7; background:#e4f2ff; }
    body.offers-page .offer-tag-duration { color:#1986c7; background:#e3f5ff; }
    body.offers-page .offer-card-footer { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:10px; }
    body.offers-page .offer-card-footer p { overflow:hidden; margin:0; color:#63759e; font-size:.73rem; text-overflow:ellipsis; white-space:nowrap; }
    body.offers-page .offer-card-footer p i { margin-right:5px; color:#ef4a5b; }
    body.offers-page .offer-card-footer>a { display:grid; width:34px; height:34px; flex:0 0 34px; place-items:center; border-radius:50%; color:#2860ef; background:#edf1ff; text-decoration:none; }
    body.offers-page .offer-card-footer>a:hover { color:#fff; background:#2860ef; }
    body.offers-page .offer-published,body.offers-page .offer-expiration { margin:6px 0 0; color:#8a96b2; font-size:.65rem; }
    body.offers-page .offers-empty { grid-column:1/-1; display:flex; min-height:260px; align-items:center; flex-direction:column; justify-content:center; padding:25px; border:1px solid #e4e9f9; border-radius:16px; background:#fff; text-align:center; }
    body.offers-page .offers-empty>span { display:grid; width:56px; height:56px; margin-bottom:12px; place-items:center; border-radius:50%; color:#2c61ea; background:#eaf0ff; font-size:1.3rem; }
    body.offers-page .offers-empty h3 { margin:0 0 5px; color:#173273; font-size:1.1rem; }
    body.offers-page .offers-empty p { margin:0 0 12px; color:#6d7da3; font-size:.82rem; }
    body.offers-page .offers-empty a { color:#2054e8; font-size:.8rem; font-weight:700; text-decoration:none; }
    body.offers-page .offers-pagination { display:flex; justify-content:center; margin-top:23px; }
    body.offers-page .offers-pagination-pages { display:flex; flex-wrap:wrap; justify-content:center; gap:6px; }
    body.offers-page .offers-pagination-page { display:grid; min-width:40px; height:40px; padding:0 10px; place-items:center; border:1px solid #dfe6fb; border-radius:9px; color:#2451ee; background:#fff; font-size:.85rem; font-weight:700; text-decoration:none; }
    body.offers-page .offers-pagination-page:hover,body.offers-page .offers-pagination-page.is-active { border-color:#2451ee; color:#fff; background:#2451ee; }
    body.offers-page .offers-dashboard-shell { display:grid; min-height:100vh; grid-template-columns:184px minmax(0,1fr); background:#f3f5ff; }
    body.offers-page .offers-dashboard-shell .dashboard-sidebar { position:sticky; top:0; display:flex; height:100vh; min-height:620px; flex-direction:column; padding:22px 13px 15px; overflow:hidden; color:#d5e0ff; background:radial-gradient(circle at 20% 76%,rgba(36,89,240,.42),transparent 25%),linear-gradient(180deg,#133782,#102b71 75%,#102766); }
    body.offers-page .offers-dashboard-shell .dashboard-brand { display:flex; align-items:center; flex-direction:column; gap:4px; padding:0 6px 26px; color:#fff; text-align:center; text-decoration:none; }
    body.offers-page .offers-dashboard-shell .dashboard-brand img { width:76px; height:65px; object-fit:contain; filter:brightness(0) invert(1); }
    body.offers-page .offers-dashboard-shell .dashboard-brand span { font-family:'Manrope',sans-serif; font-size:.92rem; font-weight:800; }
    body.offers-page .offers-dashboard-shell .dashboard-nav { display:grid; gap:7px; }
    body.offers-page .offers-dashboard-shell .dashboard-nav-link { display:flex; min-height:42px; align-items:center; gap:12px; padding:0 13px; border-radius:10px; color:#bdcbee; font-size:.78rem; font-weight:600; text-decoration:none; }
    body.offers-page .offers-dashboard-shell .dashboard-nav-link i { width:17px; font-size:.95rem; text-align:center; }
    body.offers-page .offers-dashboard-shell .dashboard-nav-link:hover { color:#fff; background:rgba(255,255,255,.12); }
    body.offers-page .offers-dashboard-shell .dashboard-nav-link.active { color:#fff; background:linear-gradient(105deg,#3269ff,#2551e5); box-shadow:0 8px 18px rgba(2,20,85,.22); }
    body.offers-page .offers-dashboard-shell .dashboard-sidebar-footer { margin:auto 9px 18px; padding:18px 12px 12px; border-top:1px solid rgba(255,255,255,.14); color:#d6e1ff; }
    body.offers-page .offers-dashboard-shell .dashboard-cap { display:grid; width:54px; height:50px; margin-bottom:10px; place-items:center; color:#72aaff; font-size:2rem; }
    body.offers-page .offers-dashboard-shell .dashboard-sidebar-footer p { margin:0; font-size:.75rem; line-height:1.55; }
    body.offers-page .offers-dashboard-shell .dashboard-sidebar-footer strong { color:#73aaff; }
    body.offers-page .offers-dashboard-shell .dashboard-logout { margin-top:4px; }
    body.offers-page .offers-dashboard-shell .dashboard-logout button { display:flex; width:100%; min-height:41px; align-items:center; gap:12px; padding:0 13px; border:0; border-radius:9px; color:#bdcbee; background:transparent; font:600 .78rem 'DM Sans',sans-serif; text-align:left; }
    body.offers-page .offers-dashboard-shell .dashboard-logout button:hover { color:#fff; background:rgba(255,255,255,.12); }
    body.offers-page .offers-dashboard-shell .dashboard-logout i { width:17px; text-align:center; }
    body.offers-page .offers-main { min-width:0; }
    body.offers-page .offers-topbar { position:relative; z-index:3; display:flex; min-height:78px; align-items:center; justify-content:space-between; gap:18px; padding:10px clamp(16px,2.4vw,34px); border-bottom:1px solid #e4e9fb; border-radius:0 0 0 18px; background:rgba(255,255,255,.87); box-shadow:0 5px 16px rgba(34,65,161,.04); backdrop-filter:blur(14px); }
    body.offers-page .offers-topnav { display:flex; align-self:stretch; align-items:center; gap:clamp(14px,2.2vw,34px); }
    body.offers-page .offers-topnav a { position:relative; display:flex; height:100%; align-items:center; color:#334879; font-size:.78rem; font-weight:600; text-decoration:none; white-space:nowrap; }
    body.offers-page .offers-topnav a:hover,body.offers-page .offers-topnav a.active { color:#174fdf; }
    body.offers-page .offers-topnav a.active::after { position:absolute; right:0; bottom:0; left:0; height:3px; border-radius:3px; background:#2459ef; content:''; }
    body.offers-page .offers-top-actions { display:flex; align-items:center; gap:12px; }
    body.offers-page .offers-icon-button { position:relative; display:grid; width:40px; height:40px; flex:0 0 40px; place-items:center; border:1px solid #edf0fc; border-radius:50%; color:#142d70; background:#fff; box-shadow:0 3px 12px rgba(26,52,133,.08); text-decoration:none; }
    body.offers-page .offers-notification>span { position:absolute; top:-4px; right:-3px; display:grid; min-width:18px; height:18px; padding:0 4px; place-items:center; border-radius:20px; color:#fff; background:#ed3451; font-size:.64rem; font-weight:800; }
    body.offers-page .offers-user { display:flex; align-items:center; gap:9px; color:#193274; font-size:.74rem; text-decoration:none; white-space:nowrap; }
    body.offers-page .offers-user>img,body.offers-page .offers-user>span { display:grid; width:38px; height:38px; flex:0 0 38px; place-items:center; border-radius:50%; object-fit:cover; color:#fff; background:linear-gradient(135deg,#82aaff,#3459db); font-weight:800; }
    body.offers-page .offers-user strong { max-width:150px; overflow:hidden; text-overflow:ellipsis; }
    body.offers-page .offers-user>i { font-size:.68rem; }
    body.offers-page .offers-page-student-content { max-width:1600px; margin:0 auto; padding:0 clamp(16px,2vw,32px) 34px; }
    body.offers-page .offers-page-student-content .offers-hero { min-height:220px; margin:0 calc(clamp(16px,2vw,32px) * -1) 0; padding:28px clamp(24px,4vw,54px); border-radius:0; }
    body.offers-page .offers-page-student-content .offers-filter-card { margin:-22px 0 28px; }
    body.offers-page .offers-page-student-content .offers-grid { gap:14px; }
    body.offers-page .offers-page-student-content .offers-results-heading { margin-top:0; }
    html[data-theme="dark"] body.offers-page { --offers-navy:#e3ecff; --offers-ink:#e3ecff; --offers-muted:#abb9dc; --offers-bg:#0d1730; background:#0d1730; color:#e3ecff; }
    html[data-theme="dark"] body.offers-page .offers-hero { background-color:#17274d; background-image:linear-gradient(90deg,#132349 0%,rgba(19,35,73,.96) 34%,rgba(19,35,73,.56) 64%,rgba(19,35,73,.22) 100%),url('{{ asset('images/stg2.png') }}'); }
    html[data-theme="dark"] body.offers-page .offers-filter-card,html[data-theme="dark"] body.offers-page .offer-card,html[data-theme="dark"] body.offers-page .offers-empty { border-color:#293b68; background:#152345; }
    html[data-theme="dark"] body.offers-page .offers-hero h1,html[data-theme="dark"] body.offers-page .offers-results-heading h2,html[data-theme="dark"] body.offers-page .offer-title-wrap h3,html[data-theme="dark"] body.offers-page .offers-empty h3 { color:#e4ecff; }
    html[data-theme="dark"] body.offers-page .offers-filter-field label,html[data-theme="dark"] body.offers-page .offers-topnav a,html[data-theme="dark"] body.offers-page .offers-user { color:#d9e4ff; }
    html[data-theme="dark"] body.offers-page .offers-filter-field input,html[data-theme="dark"] body.offers-page .offers-filter-field select { border-color:#334773; color:#e3ecff; background:#101c39; }
    html[data-theme="dark"] body.offers-page .offers-filter-field input::placeholder { color:#aab8d9; }
    html[data-theme="dark"] body.offers-page .offers-main { background:#0d1730; }
    html[data-theme="dark"] body.offers-page .offers-topbar { border-color:#263864; background:rgba(21,35,69,.9); }
    html[data-theme="dark"] body.offers-page .offers-icon-button { border-color:#344772; color:#e5edff; background:#1b2b50; }
    html[data-theme="dark"] body.offers-page .offer-description,html[data-theme="dark"] body.offers-page .offer-card-footer p,html[data-theme="dark"] body.offers-page .offers-results-heading p,html[data-theme="dark"] body.offers-page .offer-published,html[data-theme="dark"] body.offers-page .offer-expiration { color:#b7c5e5; }
    @media(max-width:1200px) {
        body.offers-page .offers-filter-form { grid-template-columns:repeat(3,minmax(0,1fr)); }
        body.offers-page .offers-search-submit { grid-column:3; }
    }
    @media(max-width:1100px) {
        body.offers-page .offers-dashboard-shell { grid-template-columns:82px minmax(0,1fr); }
        body.offers-page .offers-dashboard-shell .dashboard-sidebar { align-items:center; padding-inline:9px; }
        body.offers-page .offers-dashboard-shell .dashboard-brand { padding-bottom:28px; }
        body.offers-page .offers-dashboard-shell .dashboard-brand img { width:52px; height:48px; }
        body.offers-page .offers-dashboard-shell .dashboard-brand span,body.offers-page .offers-dashboard-shell .dashboard-nav-link span,body.offers-page .offers-dashboard-shell .dashboard-sidebar-footer,body.offers-page .offers-dashboard-shell .dashboard-logout span { display:none; }
        body.offers-page .offers-dashboard-shell .dashboard-nav { width:100%; }
        body.offers-page .offers-dashboard-shell .dashboard-nav-link,body.offers-page .offers-dashboard-shell .dashboard-logout button { justify-content:center; padding:0; }
        body.offers-page .offers-dashboard-shell .dashboard-nav-link i,body.offers-page .offers-dashboard-shell .dashboard-logout i { width:auto; }
        body.offers-page .offers-dashboard-shell .dashboard-logout { width:100%; margin-top:auto; }
        body.offers-page .offers-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:760px) {
        body.offers-page .offers-topbar { align-items:flex-start; flex-direction:column; }
        body.offers-page .offers-topnav { width:100%; min-height:42px; gap:22px; overflow-x:auto; }
        body.offers-page .offers-top-actions { position:absolute; top:10px; right:16px; }
        body.offers-page .offers-user strong,body.offers-page .offers-user>i { display:none; }
        body.offers-page .offers-page-student-content .offers-hero { min-height:200px; }
        body.offers-page .offers-hero-cap { right:9%; }
        body.offers-page .offers-filter-form { grid-template-columns:repeat(2,minmax(0,1fr)); }
        body.offers-page .offers-search-submit { grid-column:1/-1; }
    }
    @media(max-width:560px) {
        body.offers-page .offers-dashboard-shell { display:block; }
        body.offers-page .offers-dashboard-shell .dashboard-sidebar { position:static; height:auto; min-height:0; flex-direction:row; justify-content:center; padding:8px 10px; }
        body.offers-page .offers-dashboard-shell .dashboard-brand,body.offers-page .offers-dashboard-shell .dashboard-logout { display:none; }
        body.offers-page .offers-dashboard-shell .dashboard-nav { display:flex; justify-content:flex-start; gap:7px; overflow-x:auto; }
        body.offers-page .offers-dashboard-shell .dashboard-nav-link { width:42px; min-width:42px; height:42px; min-height:42px; }
        body.offers-page .offers-page-student-content { padding-inline:14px; }
        body.offers-page .offers-page-student-content .offers-hero { margin-inline:-14px; padding-inline:20px; }
        body.offers-page .offers-page-public-content .offers-hero { min-height:195px; }
        body.offers-page .offers-grid { grid-template-columns:1fr; }
        body.offers-page .offers-filter-form { grid-template-columns:1fr; }
        body.offers-page .offers-search-submit { grid-column:auto; }
        body.offers-page .offers-filter-card { padding:14px; }
        body.offers-page .offers-page-public-content .offers-filter-card { margin-top:-18px; }
        body.offers-page .offers-results-heading h2 { font-size:1.1rem; }
    }
</style>
@endsection
