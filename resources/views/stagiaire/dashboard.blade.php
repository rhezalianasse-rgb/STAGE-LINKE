@extends('layouts.app')

@section('body-class', 'stagiaire-dashboard-page')
@section('title', 'Tableau de bord')

@section('content')
<div class="student-dashboard">
    @include('stagiaire.partials.sidebar')

    <div class="dashboard-main">
        <header class="dashboard-topbar">
            <form class="dashboard-search" action="{{ route('offres.index') }}" method="GET" role="search">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" name="keyword" aria-label="Rechercher une offre" placeholder="Rechercher une offre, une entreprise...">
            </form>

            <div class="dashboard-top-actions">
                <button class="dashboard-icon-button" type="button" data-theme-toggle aria-label="Activer le mode sombre" title="Activer le mode sombre" aria-pressed="false">
                    <i class="fa-solid fa-moon" data-theme-icon aria-hidden="true"></i>
                </button>
                <a class="dashboard-icon-button dashboard-notification" href="{{ route('notifications.index') }}" aria-label="Notifications">
                    <i class="fa-regular fa-bell" aria-hidden="true"></i>
                    @if(($stats['notifications_non_lues'] ?? 0) > 0)
                        <span>{{ $stats['notifications_non_lues'] }}</span>
                    @endif
                </a>
                <a class="dashboard-user" href="{{ route('stagiaire.profil') }}">
                    @if($stagiaire->photo_path)
                        <img src="{{ route('profile-photos.show', ['filename' => basename($stagiaire->photo_path)]) }}" alt="Photo de {{ Auth::user()->prenom ?? 'profil' }}">
                    @else
                        <span class="dashboard-avatar-fallback">{{ strtoupper(substr(Auth::user()->prenom ?? 'S', 0, 1)) }}</span>
                    @endif
                    <span class="dashboard-user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </a>
            </div>
        </header>

        <div class="dashboard-content">
            <section class="dashboard-welcome">
                <div>
                    <p class="dashboard-welcome-label">VOTRE ESPACE STAGIAIRE</p>
                    <h1>Bienvenue, <span>{{ Auth::user()->prenom ?? 'Stagiaire' }}</span> !</h1>
                    <p>Voici un aperçu de votre activité sur Stage Link <span aria-hidden="true">🚀</span></p>
                </div>
                <i class="fa-solid fa-paper-plane dashboard-welcome-plane" aria-hidden="true"></i>
            </section>

            <section class="dashboard-stats" aria-label="Résumé de votre activité">
                <article class="dashboard-stat-card stat-applications">
                    <span class="dashboard-stat-icon"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                    <div class="dashboard-stat-copy">
                        <span>Total candidatures</span>
                        <strong>{{ $stats['total_candidatures'] ?? 0 }}</strong>
                    </div>
                    <i class="fa-solid fa-arrow-trend-up dashboard-stat-trend" aria-hidden="true"></i>
                </article>
                <article class="dashboard-stat-card stat-accepted">
                    <span class="dashboard-stat-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                    <div class="dashboard-stat-copy">
                        <span>Acceptées</span>
                        <strong>{{ $stats['candidatures_acceptees'] ?? 0 }}</strong>
                    </div>
                    <i class="fa-solid fa-arrow-trend-up dashboard-stat-trend" aria-hidden="true"></i>
                </article>
                <article class="dashboard-stat-card stat-favorites">
                    <span class="dashboard-stat-icon"><i class="fa-solid fa-star" aria-hidden="true"></i></span>
                    <div class="dashboard-stat-copy">
                        <span>Favoris</span>
                        <strong>{{ $stats['favoris'] ?? 0 }}</strong>
                    </div>
                    <i class="fa-solid fa-arrow-trend-up dashboard-stat-trend" aria-hidden="true"></i>
                </article>
                <article class="dashboard-stat-card stat-notifications">
                    <span class="dashboard-stat-icon"><i class="fa-solid fa-bell" aria-hidden="true"></i></span>
                    <div class="dashboard-stat-copy">
                        <span>Notifications non lues</span>
                        <strong>{{ $stats['notifications_non_lues'] ?? 0 }}</strong>
                    </div>
                    <i class="fa-solid fa-arrow-trend-up dashboard-stat-trend" aria-hidden="true"></i>
                </article>
            </section>

            <div class="dashboard-panels">
                <section class="dashboard-panel recent-panel">
                    <div class="dashboard-panel-heading">
                        <h2><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i> Candidatures récentes</h2>
                        <a href="{{ route('stagiaire.candidatures') }}">Voir tout <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>

                    @if(($recentCandidatures ?? collect())->isEmpty())
                        <div class="dashboard-empty">
                            <svg viewBox="0 0 220 145" role="img" aria-label="Aucune candidature pour le moment">
                                <path d="M22 117c28-23 51-24 79-3 32 23 62 13 97-11v30H22z" fill="#eef2ff"/>
                                <rect x="68" y="24" width="79" height="91" rx="12" transform="rotate(-7 68 24)" fill="#dfe7ff"/>
                                <rect x="82" y="35" width="80" height="87" rx="12" fill="#fff" stroke="#d7e0ff" stroke-width="3"/>
                                <path d="M99 55h42M99 69h36M99 83h25" stroke="#9bb3ff" stroke-width="6" stroke-linecap="round"/>
                                <circle cx="151" cy="93" r="20" fill="#4778f5"/>
                                <circle cx="151" cy="93" r="12" fill="none" stroke="#fff" stroke-width="5"/>
                                <path d="m166 108 14 14" stroke="#264bb7" stroke-width="8" stroke-linecap="round"/>
                                <path d="m54 45 8-8m-7 24-11-3m29-27 4-10" stroke="#70a4ff" stroke-width="5" stroke-linecap="round"/>
                            </svg>
                            <p>Aucune candidature récente.</p>
                            <a href="{{ route('offres.index') }}">Explorer les offres <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    @else
                        <div class="dashboard-table-wrap">
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCandidatures as $candidature)
                                        <tr>
                                            <td>{{ $candidature->offre->titre ?? 'N/A' }}</td>
                                            <td>{{ $candidature->offre->entreprise->nom_entreprise ?? 'N/A' }}</td>
                                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($candidature->statut == 'acceptee')
                                                    <span class="dashboard-status status-accepted">Acceptée</span>
                                                @elseif($candidature->statut == 'refusee')
                                                    <span class="dashboard-status status-refused">Refusée</span>
                                                @else
                                                    <span class="dashboard-status status-pending">En attente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <aside class="dashboard-panel quick-panel">
                    <div class="dashboard-panel-heading">
                        <h2><i class="fa-solid fa-bolt" aria-hidden="true"></i> Liens rapides</h2>
                    </div>
                    <div class="dashboard-quick-links">
                        <a class="quick-link quick-search" href="{{ route('offres.index') }}">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><span>Chercher un stage</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a class="quick-link quick-profile" href="{{ route('stagiaire.profil') }}">
                            <i class="fa-solid fa-user-pen" aria-hidden="true"></i><span>Mettre à jour mon profil</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a class="quick-link quick-skills" href="{{ route('stagiaire.competences') }}">
                            <i class="fa-solid fa-trophy" aria-hidden="true"></i><span>Gérer mes compétences</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="quick-cityline" aria-hidden="true"><i class="fa-solid fa-city"></i></div>
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    body.stagiaire-dashboard-page { --dash-navy:#122d77; --dash-blue:#2451ee; --dash-text:#17295f; --dash-muted:#65759f; --dash-line:#e4e9fb; --dash-bg:#f3f5ff; background:var(--dash-bg); color:var(--dash-text); font-family:'DM Sans',sans-serif; }
    body.stagiaire-dashboard-page h1,body.stagiaire-dashboard-page h2 { font-family:'Manrope',sans-serif; letter-spacing:-.025em; }
    body.stagiaire-dashboard-page .navbar,body.stagiaire-dashboard-page footer { display:none; }
    body.stagiaire-dashboard-page main { display:block; min-height:100vh; padding:0; }
    body.stagiaire-dashboard-page .student-dashboard { display:grid; min-height:100vh; grid-template-columns:184px minmax(0,1fr); }
    body.stagiaire-dashboard-page .dashboard-sidebar { position:sticky; top:0; display:flex; height:100vh; min-height:620px; flex-direction:column; padding:22px 13px 15px; overflow:hidden; color:#d5e0ff; background:radial-gradient(circle at 20% 76%,rgba(36,89,240,.42),transparent 25%),linear-gradient(180deg,#133782,#102b71 75%,#102766); }
    body.stagiaire-dashboard-page .dashboard-brand { display:flex; align-items:center; flex-direction:column; gap:4px; padding:0 6px 26px; color:#fff; text-align:center; text-decoration:none; }
    body.stagiaire-dashboard-page .dashboard-brand img { width:76px; height:65px; object-fit:contain; filter:brightness(0) invert(1); }
    body.stagiaire-dashboard-page .dashboard-brand span { font-family:'Manrope',sans-serif; font-size:.92rem; font-weight:800; letter-spacing:.02em; }
    body.stagiaire-dashboard-page .dashboard-nav { display:grid; gap:7px; }
    body.stagiaire-dashboard-page .dashboard-nav-link { display:flex; min-height:42px; align-items:center; gap:12px; padding:0 13px; border-radius:10px; color:#bdcbee; font-size:.78rem; font-weight:600; text-decoration:none; transition:background .16s ease,color .16s ease; }
    body.stagiaire-dashboard-page .dashboard-nav-link i { width:17px; font-size:.95rem; text-align:center; }
    body.stagiaire-dashboard-page .dashboard-nav-link:hover { color:#fff; background:rgba(255,255,255,.12); }
    body.stagiaire-dashboard-page .dashboard-nav-link.active { color:#fff; background:linear-gradient(105deg,#3269ff,#2551e5); box-shadow:0 8px 18px rgba(2,20,85,.22); }
    body.stagiaire-dashboard-page .dashboard-sidebar-footer { margin:auto 9px 18px; padding:18px 12px 12px; border-top:1px solid rgba(255,255,255,.14); color:#d6e1ff; }
    body.stagiaire-dashboard-page .dashboard-cap { display:grid; width:54px; height:50px; margin-bottom:10px; place-items:center; color:#72aaff; font-size:2rem; }
    body.stagiaire-dashboard-page .dashboard-sidebar-footer p { margin:0; font-size:.75rem; line-height:1.55; }
    body.stagiaire-dashboard-page .dashboard-sidebar-footer strong { color:#73aaff; }
    body.stagiaire-dashboard-page .dashboard-logout { margin-top:4px; }
    body.stagiaire-dashboard-page .dashboard-logout button { display:flex; width:100%; min-height:41px; align-items:center; gap:12px; padding:0 13px; border:0; border-radius:9px; color:#bdcbee; background:transparent; font:600 .78rem 'DM Sans',sans-serif; text-align:left; }
    body.stagiaire-dashboard-page .dashboard-logout button:hover { color:#fff; background:rgba(255,255,255,.12); }
    body.stagiaire-dashboard-page .dashboard-logout i { width:17px; text-align:center; }
    body.stagiaire-dashboard-page .dashboard-main { min-width:0; }
    body.stagiaire-dashboard-page .dashboard-topbar { position:relative; z-index:2; display:flex; height:78px; align-items:center; justify-content:space-between; gap:22px; padding:0 clamp(20px,3vw,42px); border-bottom:1px solid rgba(218,225,249,.8); background:rgba(255,255,255,.84); backdrop-filter:blur(14px); }
    body.stagiaire-dashboard-page .dashboard-search { display:flex; width:min(475px,48%); height:46px; align-items:center; gap:12px; padding:0 16px; border:1px solid #edf0fc; border-radius:24px; color:#51679d; background:#f1f3fc; }
    body.stagiaire-dashboard-page .dashboard-search i { font-size:.95rem; }
    body.stagiaire-dashboard-page .dashboard-search input { width:100%; min-width:0; padding:0; border:0; outline:0; color:#1e3374; background:transparent; font:.83rem 'DM Sans',sans-serif; }
    body.stagiaire-dashboard-page .dashboard-search input::placeholder { color:#5f6e9a; opacity:1; }
    body.stagiaire-dashboard-page .dashboard-top-actions { display:flex; align-items:center; gap:13px; }
    body.stagiaire-dashboard-page .dashboard-icon-button { position:relative; display:grid; width:42px; height:42px; place-items:center; border:1px solid #edf0fc; border-radius:50%; color:#122e79; background:#fff; box-shadow:0 3px 12px rgba(26,52,133,.08); text-decoration:none; }
    body.stagiaire-dashboard-page .dashboard-icon-button:hover { color:#2451ee; border-color:#cdd8ff; }
    body.stagiaire-dashboard-page .dashboard-notification>span { position:absolute; top:-4px; right:-3px; display:grid; min-width:18px; height:18px; padding:0 4px; place-items:center; border-radius:20px; color:#fff; background:#ed3451; font-size:.65rem; font-weight:800; }
    body.stagiaire-dashboard-page .dashboard-user { display:flex; align-items:center; gap:10px; color:#193274; font-size:.78rem; font-weight:700; text-decoration:none; }
    body.stagiaire-dashboard-page .dashboard-user>img,body.stagiaire-dashboard-page .dashboard-avatar-fallback { width:39px; height:39px; flex:0 0 39px; border-radius:50%; object-fit:cover; }
    body.stagiaire-dashboard-page .dashboard-avatar-fallback { display:grid; place-items:center; color:#fff; background:linear-gradient(135deg,#82aaff,#3459db); font-weight:800; }
    body.stagiaire-dashboard-page .dashboard-user>i { margin-left:2px; font-size:.7rem; }
    body.stagiaire-dashboard-page .dashboard-content { max-width:1600px; margin:0 auto; padding:30px clamp(20px,3vw,42px) 42px; }
    body.stagiaire-dashboard-page .dashboard-welcome { position:relative; display:flex; min-height:94px; align-items:center; justify-content:space-between; overflow:hidden; padding:0 8px 19px; }
    body.stagiaire-dashboard-page .dashboard-welcome::after { position:absolute; top:-119px; right:-64px; width:430px; height:260px; border-radius:50%; background:linear-gradient(135deg,rgba(212,224,255,.1),rgba(174,199,255,.62)); content:''; transform:rotate(-12deg); }
    body.stagiaire-dashboard-page .dashboard-welcome-label { margin:0 0 7px; color:#5471bc; font-size:.65rem; font-weight:800; letter-spacing:.13em; }
    body.stagiaire-dashboard-page .dashboard-welcome h1 { margin:0; color:#183270; font-size:clamp(1.7rem,2.4vw,2.25rem); font-weight:800; }
    body.stagiaire-dashboard-page .dashboard-welcome h1 span { color:#2858eb; }
    body.stagiaire-dashboard-page .dashboard-welcome>div>p:last-child { margin:5px 0 0; color:#5d6e9e; font-size:.9rem; }
    body.stagiaire-dashboard-page .dashboard-welcome-plane { position:absolute; z-index:1; top:8px; right:12%; color:#3773fb; font-size:2.4rem; transform:rotate(-18deg); }
    body.stagiaire-dashboard-page .dashboard-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:15px; margin-bottom:26px; }
    body.stagiaire-dashboard-page .dashboard-stat-card { position:relative; display:flex; min-width:0; min-height:108px; align-items:center; gap:14px; overflow:hidden; padding:16px; border:1px solid rgba(221,228,249,.8); border-radius:17px; background:rgba(255,255,255,.9); box-shadow:0 7px 18px rgba(31,59,142,.08); }
    body.stagiaire-dashboard-page .dashboard-stat-icon { display:grid; width:58px; height:58px; flex:0 0 58px; place-items:center; border-radius:15px; font-size:1.3rem; }
    body.stagiaire-dashboard-page .dashboard-stat-copy { display:flex; min-width:0; flex-direction:column; gap:6px; }
    body.stagiaire-dashboard-page .dashboard-stat-copy span { color:#4f6294; font-size:.77rem; font-weight:600; line-height:1.25; }
    body.stagiaire-dashboard-page .dashboard-stat-copy strong { color:#152c72; font-family:'Manrope',sans-serif; font-size:1.55rem; line-height:1; }
    body.stagiaire-dashboard-page .dashboard-stat-trend { position:absolute; right:14px; bottom:14px; font-size:1.2rem; opacity:.72; }
    body.stagiaire-dashboard-page .stat-applications .dashboard-stat-icon { color:#1d5af2; background:#e4ebff; }
    body.stagiaire-dashboard-page .stat-applications .dashboard-stat-trend { color:#3379fb; }
    body.stagiaire-dashboard-page .stat-accepted .dashboard-stat-icon { color:#078657; background:#dcf4ea; }
    body.stagiaire-dashboard-page .stat-accepted .dashboard-stat-trend { color:#14a36d; }
    body.stagiaire-dashboard-page .stat-favorites .dashboard-stat-icon { color:#f28a13; background:#fff0dd; }
    body.stagiaire-dashboard-page .stat-favorites .dashboard-stat-trend { color:#f2942a; }
    body.stagiaire-dashboard-page .stat-notifications .dashboard-stat-icon { color:#7944ee; background:#eee5ff; }
    body.stagiaire-dashboard-page .stat-notifications .dashboard-stat-trend { color:#8c58fb; }
    body.stagiaire-dashboard-page .dashboard-panels { display:grid; grid-template-columns:minmax(0,1.8fr) minmax(280px,.9fr); gap:22px; }
    body.stagiaire-dashboard-page .dashboard-panel { min-width:0; min-height:350px; overflow:hidden; border:1px solid #e5eafd; border-radius:18px; background:rgba(255,255,255,.92); box-shadow:0 8px 22px rgba(31,59,142,.07); }
    body.stagiaire-dashboard-page .dashboard-panel-heading { display:flex; min-height:68px; align-items:center; justify-content:space-between; gap:14px; padding:0 20px; }
    body.stagiaire-dashboard-page .dashboard-panel-heading h2 { display:flex; align-items:center; gap:11px; margin:0; color:#1b347b; font-size:1.02rem; font-weight:800; }
    body.stagiaire-dashboard-page .dashboard-panel-heading h2 i { color:#2159ed; font-size:1.15rem; }
    body.stagiaire-dashboard-page .dashboard-panel-heading>a { flex:0 0 auto; color:#204ee0; font-size:.76rem; font-weight:700; text-decoration:none; }
    body.stagiaire-dashboard-page .dashboard-panel-heading>a:hover { color:#122d91; }
    body.stagiaire-dashboard-page .dashboard-panel-heading>a i { margin-left:5px; }
    body.stagiaire-dashboard-page .dashboard-table-wrap { overflow-x:auto; padding:0 18px 18px; }
    body.stagiaire-dashboard-page .dashboard-table { width:100%; border-collapse:separate; border-spacing:0; text-align:left; }
    body.stagiaire-dashboard-page .dashboard-table th { padding:12px 14px; color:#576b9d; background:#f3f5ff; font-size:.74rem; font-weight:700; white-space:nowrap; }
    body.stagiaire-dashboard-page .dashboard-table th:first-child { border-radius:9px 0 0 9px; }
    body.stagiaire-dashboard-page .dashboard-table th:last-child { border-radius:0 9px 9px 0; }
    body.stagiaire-dashboard-page .dashboard-table td { padding:14px; border-bottom:1px solid #edf0fa; color:#4c5d8a; font-size:.76rem; }
    body.stagiaire-dashboard-page .dashboard-status { display:inline-block; padding:5px 9px; border-radius:20px; font-size:.68rem; font-weight:700; white-space:nowrap; }
    body.stagiaire-dashboard-page .status-accepted { color:#087a4d; background:#e0f6ec; }
    body.stagiaire-dashboard-page .status-refused { color:#b8344c; background:#ffeaee; }
    body.stagiaire-dashboard-page .status-pending { color:#9a6405; background:#fff3d8; }
    body.stagiaire-dashboard-page .dashboard-empty { display:flex; min-height:270px; align-items:center; flex-direction:column; justify-content:center; padding:8px 20px 20px; text-align:center; }
    body.stagiaire-dashboard-page .dashboard-empty svg { width:190px; max-width:60%; height:130px; }
    body.stagiaire-dashboard-page .dashboard-empty p { margin:4px 0 13px; color:#526493; font-size:.88rem; font-weight:600; }
    body.stagiaire-dashboard-page .dashboard-empty a { color:#2957e7; font-size:.75rem; font-weight:700; text-decoration:none; }
    body.stagiaire-dashboard-page .dashboard-empty a:hover { text-decoration:underline; }
    body.stagiaire-dashboard-page .quick-panel { position:relative; padding-bottom:70px; }
    body.stagiaire-dashboard-page .quick-panel .dashboard-panel-heading h2 i { color:#f5a928; }
    body.stagiaire-dashboard-page .dashboard-quick-links { display:grid; gap:11px; padding:4px 18px 0; }
    body.stagiaire-dashboard-page .quick-link { display:flex; min-height:49px; align-items:center; gap:12px; padding:0 14px; border-radius:12px; font-size:.78rem; font-weight:700; text-decoration:none; transition:transform .16s ease,filter .16s ease; }
    body.stagiaire-dashboard-page .quick-link:hover { filter:brightness(.98); transform:translateX(2px); }
    body.stagiaire-dashboard-page .quick-link>i:first-child { font-size:1.05rem; }
    body.stagiaire-dashboard-page .quick-link>span { flex:1; }
    body.stagiaire-dashboard-page .quick-link>i:last-child { font-size:.75rem; }
    body.stagiaire-dashboard-page .quick-search { color:#164ae3; background:#e9efff; }
    body.stagiaire-dashboard-page .quick-profile { color:#3e518f; background:#edf0fd; }
    body.stagiaire-dashboard-page .quick-skills { color:#07824d; background:#e1f5eb; }
    body.stagiaire-dashboard-page .quick-cityline { position:absolute; right:0; bottom:0; left:0; display:flex; height:58px; align-items:flex-end; overflow:hidden; color:#b9ccff; opacity:.7; }
    body.stagiaire-dashboard-page .quick-cityline::before { position:absolute; right:-5%; bottom:-33px; left:-5%; height:76px; border-radius:50% 50% 0 0; background:linear-gradient(180deg,rgba(223,233,255,.18),#dce7ff); content:''; }
    body.stagiaire-dashboard-page .quick-cityline i { position:relative; width:100%; padding:0 18px 6px; font-size:2.8rem; text-align:right; }
    html[data-theme="dark"] body.stagiaire-dashboard-page { --dash-text:#e5edff; --dash-muted:#aab8dc; --dash-line:#263864; --dash-bg:#0d1730; background:var(--dash-bg); color:var(--dash-text); }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-main { background:#0d1730; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-topbar,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-stat-card,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-panel { border-color:#263864; background:#152345; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-search { border-color:#263864; background:#1b2b50; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-search input { color:#e5edff; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-search input::placeholder,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-stat-copy span,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-table td { color:#b7c5e5; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-icon-button { border-color:#344772; color:#e5edff; background:#1b2b50; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-user,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-welcome h1,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-panel-heading h2,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-stat-copy strong { color:#e5edff; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-welcome>div>p:last-child,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-empty p { color:#b7c5e5; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-welcome-label { color:#9bb7ff; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-welcome h1 span,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-welcome-plane { color:#89a9ff; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-panel-heading>a,html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-empty a { color:#89a9ff; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-table th { color:#ccd7f1; background:#1b2b50; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .dashboard-table td { border-color:#263864; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .quick-search { color:#c7d7ff; background:#203769; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .quick-profile { color:#d0d9f3; background:#29385f; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .quick-skills { color:#a8e7c7; background:#1c493d; }
    html[data-theme="dark"] body.stagiaire-dashboard-page .quick-cityline::before { background:linear-gradient(180deg,rgba(31,52,94,.18),#1b2b50); }
    @media(max-width:1100px) {
        body.stagiaire-dashboard-page .student-dashboard { grid-template-columns:82px minmax(0,1fr); }
        body.stagiaire-dashboard-page .dashboard-sidebar { align-items:center; padding-inline:9px; }
        body.stagiaire-dashboard-page .dashboard-brand { padding-bottom:28px; }
        body.stagiaire-dashboard-page .dashboard-brand img { width:52px; height:48px; }
        body.stagiaire-dashboard-page .dashboard-brand span,body.stagiaire-dashboard-page .dashboard-nav-link span,body.stagiaire-dashboard-page .dashboard-sidebar-footer,body.stagiaire-dashboard-page .dashboard-logout span { display:none; }
        body.stagiaire-dashboard-page .dashboard-nav { width:100%; }
        body.stagiaire-dashboard-page .dashboard-nav-link,body.stagiaire-dashboard-page .dashboard-logout button { justify-content:center; padding:0; }
        body.stagiaire-dashboard-page .dashboard-nav-link i,body.stagiaire-dashboard-page .dashboard-logout i { width:auto; }
        body.stagiaire-dashboard-page .dashboard-logout { width:100%; margin-top:auto; }
        body.stagiaire-dashboard-page .dashboard-stats { grid-template-columns:repeat(2,minmax(0,1fr)); }
        body.stagiaire-dashboard-page .dashboard-panels { grid-template-columns:minmax(0,1.4fr) minmax(260px,.9fr); gap:16px; }
    }
    @media(max-width:760px) {
        body.stagiaire-dashboard-page .dashboard-topbar { height:auto; min-height:72px; flex-wrap:wrap; padding-block:12px; }
        body.stagiaire-dashboard-page .dashboard-search { width:100%; flex:1 1 100%; }
        body.stagiaire-dashboard-page .dashboard-top-actions { width:100%; justify-content:flex-end; }
        body.stagiaire-dashboard-page .dashboard-panels { grid-template-columns:1fr; }
        body.stagiaire-dashboard-page .dashboard-panel { min-height:300px; }
    }
    @media(max-width:560px) {
        body.stagiaire-dashboard-page .student-dashboard { display:block; }
        body.stagiaire-dashboard-page .dashboard-sidebar { position:static; height:auto; min-height:0; flex-direction:row; justify-content:center; padding:8px 10px; }
        body.stagiaire-dashboard-page .dashboard-brand,body.stagiaire-dashboard-page .dashboard-logout { display:none; }
        body.stagiaire-dashboard-page .dashboard-nav { display:flex; justify-content:flex-start; gap:7px; overflow-x:auto; }
        body.stagiaire-dashboard-page .dashboard-nav-link { width:42px; min-width:42px; height:42px; min-height:42px; }
        body.stagiaire-dashboard-page .dashboard-content { padding:22px 14px 30px; }
        body.stagiaire-dashboard-page .dashboard-topbar { padding:11px 14px; }
        body.stagiaire-dashboard-page .dashboard-user-name { display:none; }
        body.stagiaire-dashboard-page .dashboard-welcome { min-height:100px; }
        body.stagiaire-dashboard-page .dashboard-welcome-plane { right:5%; font-size:1.8rem; }
        body.stagiaire-dashboard-page .dashboard-stats { gap:10px; }
        body.stagiaire-dashboard-page .dashboard-stat-card { min-height:98px; gap:9px; padding:11px; border-radius:13px; }
        body.stagiaire-dashboard-page .dashboard-stat-icon { width:43px; height:43px; flex-basis:43px; border-radius:12px; font-size:1rem; }
        body.stagiaire-dashboard-page .dashboard-stat-copy span { font-size:.68rem; }
        body.stagiaire-dashboard-page .dashboard-stat-copy strong { font-size:1.25rem; }
        body.stagiaire-dashboard-page .dashboard-stat-trend { right:8px; bottom:8px; font-size:.9rem; }
        body.stagiaire-dashboard-page .dashboard-panel-heading { padding-inline:14px; }
        body.stagiaire-dashboard-page .dashboard-table-wrap { padding-inline:12px; }
        body.stagiaire-dashboard-page .dashboard-table th,body.stagiaire-dashboard-page .dashboard-table td { padding-inline:9px; }
    }
</style>
@endsection
