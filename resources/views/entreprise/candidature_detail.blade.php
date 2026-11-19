@extends('layouts.app')

@section('body-class', 'candidate-detail-page')
@section('title', 'Détail de la Candidature')

@section('content')
<aside class="candidate-sidebar" aria-label="Navigation entreprise">
    <a class="candidate-sidebar-brand" href="{{ route('entreprise.dashboard') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Stage Link">
    </a>

    <div class="candidate-sidebar-label">Menu <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></div>
    <nav class="candidate-sidebar-nav">
        <a href="{{ route('entreprise.dashboard') }}" class="{{ request()->routeIs('entreprise.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house" aria-hidden="true"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('entreprise.offres.index') }}" class="{{ request()->routeIs('entreprise.offres.*') ? 'active' : '' }}">
            <i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Mes Offres</span>
        </a>
        <a href="{{ route('entreprise.candidatures') }}" class="{{ request()->routeIs('entreprise.candidatures*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines" aria-hidden="true"></i><span>Candidatures reçues</span>
        </a>
        <a href="{{ route('entreprise.profil') }}" class="{{ request()->routeIs('entreprise.profil') ? 'active' : '' }}">
            <i class="fa-solid fa-user" aria-hidden="true"></i><span>Mon Profil</span>
        </a>
    </nav>

    <div class="candidate-sidebar-bottom">
        <div class="candidate-sidebar-label">Notifications</div>
        <a class="candidate-sidebar-notifications" href="{{ route('notifications.index') }}">
            <span><i class="fa-regular fa-bell" aria-hidden="true"></i> Toutes les notifications</span>
            @php($unreadNotificationsCount = auth()->user()->unreadNotifications->count())
            @if($unreadNotificationsCount > 0)
                <span class="candidate-sidebar-count">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>
    </div>
</aside>

<section class="candidate-detail-hero">
    <div class="candidate-detail-hero-inner">
        <nav class="candidate-detail-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ route('entreprise.dashboard') }}" aria-label="Tableau de bord"><i class="fa-solid fa-house" aria-hidden="true"></i></a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <a href="{{ route('entreprise.candidatures') }}">Candidatures reçues</a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <span>Détail</span>
        </nav>
        <div class="candidate-detail-hero-copy">
            <p class="candidate-detail-kicker">STAGE LINK · ESPACE ENTREPRISE</p>
            <h1>Profil du <span>candidat</span></h1>
            <p>Consultez le profil, les compétences et la candidature du stagiaire.</p>
            <span class="candidate-detail-underline"></span>
        </div>
        <div class="candidate-detail-hero-art" aria-hidden="true">
            <span class="candidate-detail-hero-icon"><i class="fa-solid fa-user-graduate"></i></span>
            <span class="candidate-detail-hero-bubble candidate-detail-bubble-one"><i class="fa-solid fa-star"></i></span>
            <span class="candidate-detail-hero-bubble candidate-detail-bubble-two"><i class="fa-solid fa-briefcase"></i></span>
        </div>
    </div>
</section>

<div class="candidate-detail-content">
    <div class="candidate-detail-card">
        <section class="candidate-profile-section">
            <div class="candidate-profile-header">
                <div class="candidate-profile-photo">
                    @if($candidature->stagiaire->photo_path)
                        <img src="{{ route('profile-photos.show', ['filename' => basename($candidature->stagiaire->photo_path)]) }}" alt="Photo de {{ $candidature->stagiaire->user->prenom ?? 'profil' }}">
                    @else
                        <span><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                    @endif
                </div>
                <div class="candidate-profile-summary">
                    <p class="candidate-section-eyebrow">Candidat</p>
                    <h2>{{ $candidature->stagiaire->user->prenom ?? '' }} {{ $candidature->stagiaire->user->nom ?? '' }}</h2>
                    <p class="candidate-profile-study">
                        <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                        {{ $candidature->stagiaire->niveau_etude ?? 'Niveau non précisé' }} · {{ $candidature->stagiaire->domaine ?? 'Domaine non précisé' }}
                    </p>
                </div>
            </div>

            <div class="candidate-contact-list">
                @if(isset($candidature->stagiaire->user->email))
                    <a href="mailto:{{ $candidature->stagiaire->user->email }}">
                        <i class="fa-regular fa-envelope" aria-hidden="true"></i>{{ $candidature->stagiaire->user->email }}
                    </a>
                @endif
                @if(isset($candidature->stagiaire->user->telephone))
                    <span><i class="fa-solid fa-phone" aria-hidden="true"></i>{{ $candidature->stagiaire->user->telephone }}</span>
                @endif
            </div>

            <div class="candidate-detail-divider"></div>

            <section class="candidate-info-section">
                <h3><span><i class="fa-solid fa-star" aria-hidden="true"></i></span>Compétences</h3>
                @if(isset($candidature->stagiaire->competences) && $candidature->stagiaire->competences->count() > 0)
                    <div class="candidate-skill-list">
                        @foreach($candidature->stagiaire->competences as $comp)
                            <span>{{ $comp->nom }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="candidate-muted">Aucune compétence renseignée.</p>
                @endif
            </section>

            <section class="candidate-info-section candidate-motivation">
                <h3><span><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>Lettre de motivation</h3>
                <div class="candidate-letter-content">{{ $candidature->lettre_motivation ?? $candidature->stagiaire->lettre_motivation ?? 'Aucune lettre de motivation fournie.' }}</div>
            </section>

            @if($candidature->cv_path || $candidature->stagiaire->cv_path)
                <a href="{{ route('entreprise.candidatures.cv', $candidature->id) }}" class="candidate-cv-link">
                    <i class="fa-solid fa-download" aria-hidden="true"></i>Télécharger le CV
                </a>
            @endif
        </section>

        <aside class="candidate-detail-aside">
            <section class="candidate-detail-panel">
                <h3><span><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>Détails de l'offre</h3>
                <h4>{{ $candidature->offre->titre ?? 'Offre' }}</h4>
                <p class="candidate-application-date">
                    <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                    Candidature envoyée le {{ $candidature->created_at->format('d/m/Y à H:i') }}
                </p>
                <dl class="candidate-offer-details">
                    <div><dt>Type</dt><dd>{{ $candidature->offre->type_stage ?? 'N/A' }}</dd></div>
                    <div><dt>Durée</dt><dd>{{ $candidature->offre->duree ?? 'N/A' }}</dd></div>
                    <div><dt>Lieu</dt><dd>{{ $candidature->offre->localisation ?? 'N/A' }}</dd></div>
                </dl>
            </section>

            <section class="candidate-detail-panel candidate-decision-panel">
                <h3><span><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i></span>Décision</h3>
                <p class="candidate-status-label">Statut actuel</p>
                @if($candidature->statut == 'acceptee')
                    <span class="candidate-status accepted"><i class="fa-solid fa-circle-check" aria-hidden="true"></i>Acceptée</span>
                @elseif($candidature->statut == 'refusee')
                    <span class="candidate-status refused"><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>Refusée</span>
                @else
                    <span class="candidate-status waiting"><i class="fa-regular fa-clock" aria-hidden="true"></i>En attente</span>
                @endif

                @if($candidature->statut == 'en_attente')
                    <div class="candidate-decision-actions">
                        <form action="{{ route('entreprise.candidatures.update', $candidature->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="acceptee">
                            <button type="submit" class="candidate-accept-button"><i class="fa-solid fa-check" aria-hidden="true"></i>Accepter la candidature</button>
                        </form>
                        <form action="{{ route('entreprise.candidatures.update', $candidature->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="refusee">
                            <button type="submit" class="candidate-refuse-button"><i class="fa-solid fa-xmark" aria-hidden="true"></i>Refuser</button>
                        </form>
                    </div>
                @else
                    <p class="candidate-decision-note">Une décision a déjà été prise pour cette candidature.</p>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection

@section('styles')
<style>
    body.candidate-detail-page .candidate-sidebar {
        position: fixed;
        z-index: 1021;
        top: 8px;
        bottom: 8px;
        left: 8px;
        display: flex;
        width: 204px;
        flex-direction: column;
        padding: 18px 14px;
        overflow-y: auto;
        border: 1px solid rgba(255, 255, 255, .92);
        border-radius: 28px;
        background: linear-gradient(160deg, rgba(255, 255, 255, .96), rgba(226, 237, 255, .94));
        box-shadow: 0 12px 32px rgba(42, 87, 181, .2);
        backdrop-filter: blur(16px);
    }

    body.candidate-detail-page .candidate-sidebar-brand {
        display: flex;
        min-height: 88px;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    body.candidate-detail-page .candidate-sidebar-brand img {
        width: 100px;
        max-height: 72px;
        object-fit: contain;
    }

    body.candidate-detail-page .candidate-sidebar-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 4px 7px 13px;
        color: #536b99;
        font-size: .64rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    body.candidate-detail-page .candidate-sidebar-label i {
        color: #8296bd;
        font-size: .58rem;
    }

    body.candidate-detail-page .candidate-sidebar-nav {
        display: grid;
        gap: 7px;
    }

    body.candidate-detail-page .candidate-sidebar-nav a {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 12px;
        padding: 9px 11px;
        border-radius: 24px;
        color: #233e72;
        font-size: .76rem;
        font-weight: 700;
        text-decoration: none;
        transition: color .18s ease, background .18s ease, transform .18s ease;
    }

    body.candidate-detail-page .candidate-sidebar-nav a i {
        width: 17px;
        color: #173a76;
        text-align: center;
    }

    body.candidate-detail-page .candidate-sidebar-nav a:hover {
        color: #155bed;
        background: rgba(215, 228, 255, .74);
        transform: translateX(2px);
    }

    body.candidate-detail-page .candidate-sidebar-nav a.active {
        color: #fff;
        background: linear-gradient(100deg, #1762f2, #79a9ff);
        box-shadow: 0 6px 15px rgba(41, 101, 225, .25);
    }

    body.candidate-detail-page .candidate-sidebar-nav a.active i {
        color: #fff;
    }

    body.candidate-detail-page .candidate-sidebar-bottom {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(118, 148, 204, .22);
    }

    body.candidate-detail-page .candidate-sidebar-notifications {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 9px 8px;
        border-radius: 12px;
        color: #244276;
        font-size: .7rem;
        font-weight: 700;
        text-decoration: none;
    }

    body.candidate-detail-page .candidate-sidebar-notifications:hover {
        background: rgba(215, 228, 255, .74);
    }

    body.candidate-detail-page .candidate-sidebar-notifications i {
        margin-right: 7px;
        color: #2161ec;
    }

    body.candidate-detail-page .candidate-sidebar-count {
        display: grid;
        min-width: 20px;
        height: 20px;
        place-items: center;
        border-radius: 50%;
        color: #244276;
        background: #dce8ff;
        font-size: .64rem;
        font-weight: 800;
    }

    body.candidate-detail-page main,
    body.candidate-detail-page footer {
        margin-left: 222px;
    }

    body.candidate-detail-page {
        background: #eef3ff;
    }

    body.candidate-detail-page .container.mt-3 {
        width: calc(100% - 260px);
        margin-right: 18px !important;
        margin-left: 242px !important;
    }

    body.candidate-detail-page .candidate-detail-hero {
        position: relative;
        overflow: hidden;
        min-height: 238px;
        background-color: #e7efff;
        background-image: linear-gradient(90deg, rgba(240, 245, 255, .99) 0%, rgba(240, 245, 255, .94) 40%, rgba(229, 239, 255, .46) 69%, rgba(222, 235, 255, .15) 100%), url('{{ asset('images/stg2.png') }}');
        background-position: center, right 47%;
        background-repeat: no-repeat;
        background-size: cover;
    }

    body.candidate-detail-page .candidate-detail-hero::before,
    body.candidate-detail-page .candidate-detail-hero::after {
        position: absolute;
        border: 1px solid rgba(255, 255, 255, .6);
        border-radius: 50%;
        content: '';
    }

    body.candidate-detail-page .candidate-detail-hero::before {
        top: -130px;
        left: 44%;
        width: 310px;
        height: 310px;
        background: rgba(209, 224, 255, .22);
    }

    body.candidate-detail-page .candidate-detail-hero::after {
        right: 12%;
        bottom: -180px;
        width: 310px;
        height: 310px;
        background: rgba(211, 227, 255, .24);
    }

    body.candidate-detail-page .candidate-detail-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        max-width: 1120px;
        min-height: 238px;
        flex-direction: column;
        justify-content: center;
        margin: 0 auto;
        padding: 22px 24px 28px;
    }

    body.candidate-detail-page .candidate-detail-breadcrumb {
        display: flex;
        width: max-content;
        max-width: 100%;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding: 8px 13px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 22px;
        color: #35527f;
        background: rgba(255, 255, 255, .64);
        font-size: .73rem;
        font-weight: 600;
        white-space: nowrap;
    }

    body.candidate-detail-page .candidate-detail-breadcrumb a {
        color: #35527f;
        text-decoration: none;
    }

    body.candidate-detail-page .candidate-detail-breadcrumb a:hover {
        color: #155bed;
    }

    body.candidate-detail-page .candidate-detail-breadcrumb > i {
        color: #7890bc;
        font-size: .6rem;
    }

    body.candidate-detail-page .candidate-detail-hero-copy {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    body.candidate-detail-page .candidate-detail-kicker {
        margin: 0 0 6px;
        color: #1b5bef;
        font-size: .69rem;
        font-weight: 800;
        letter-spacing: .08em;
    }

    body.candidate-detail-page .candidate-detail-hero-copy h1 {
        margin: 0;
        color: #142f72;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(2rem, 3.8vw, 2.7rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.12;
    }

    body.candidate-detail-page .candidate-detail-hero-copy h1 span {
        color: #155cf2;
    }

    body.candidate-detail-page .candidate-detail-hero-copy > p:not(.candidate-detail-kicker) {
        max-width: 530px;
        margin: 8px 0 0;
        color: #49608f;
        font-size: .9rem;
    }

    body.candidate-detail-page .candidate-detail-underline {
        display: block;
        width: 62px;
        height: 5px;
        margin-top: 12px;
        border-radius: 5px;
        background: #1763f4;
    }

    body.candidate-detail-page .candidate-detail-hero-art {
        position: absolute;
        top: 24px;
        right: 7%;
        width: 210px;
        height: 185px;
    }

    body.candidate-detail-page .candidate-detail-hero-icon {
        position: absolute;
        right: 33px;
        bottom: 8px;
        display: grid;
        width: 135px;
        height: 135px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 30px;
        color: #fff;
        background: linear-gradient(145deg, #8eb8ff, #1d61e9 74%);
        box-shadow: 0 20px 34px rgba(29, 78, 196, .25), inset 0 2px 6px rgba(255, 255, 255, .65);
        font-size: 3.8rem;
        transform: rotate(-7deg);
    }

    body.candidate-detail-page .candidate-detail-hero-bubble {
        position: absolute;
        z-index: 2;
        display: grid;
        width: 48px;
        height: 48px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .9);
        border-radius: 15px;
        color: #2564e8;
        background: rgba(255, 255, 255, .88);
        box-shadow: 0 8px 18px rgba(39, 77, 164, .14);
        font-size: 1.15rem;
    }

    body.candidate-detail-page .candidate-detail-bubble-one {
        top: 3px;
        right: 9px;
        color: #e9a323;
    }

    body.candidate-detail-page .candidate-detail-bubble-two {
        top: 39px;
        left: 4px;
    }

    body.candidate-detail-page .candidate-detail-content {
        position: relative;
        z-index: 2;
        width: min(1120px, calc(100% - 48px));
        margin: -17px auto 34px;
    }

    body.candidate-detail-page .candidate-detail-card {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(275px, .82fr);
        gap: 28px;
        padding: 24px;
        border: 1px solid #e3e9fb;
        border-radius: 20px;
        background: rgba(255, 255, 255, .97);
        box-shadow: 0 12px 30px rgba(36, 67, 150, .1);
    }

    body.candidate-detail-page .candidate-profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    body.candidate-detail-page .candidate-profile-photo {
        display: grid;
        width: 112px;
        height: 112px;
        flex: 0 0 112px;
        place-items: center;
        overflow: hidden;
        border: 5px solid #e6efff;
        border-radius: 50%;
        color: #fff;
        background: linear-gradient(145deg, #8eb8ff, #1d61e9 74%);
        box-shadow: 0 8px 20px rgba(44, 95, 190, .2);
        font-size: 2.9rem;
    }

    body.candidate-detail-page .candidate-profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    body.candidate-detail-page .candidate-profile-summary {
        min-width: 0;
    }

    body.candidate-detail-page .candidate-section-eyebrow {
        margin: 0 0 5px;
        color: #2360ed;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    body.candidate-detail-page .candidate-profile-summary h2 {
        margin: 0;
        color: #163373;
        font-size: 1.38rem;
        font-weight: 800;
    }

    body.candidate-detail-page .candidate-profile-study {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 8px 0 0;
        color: #62759d;
        font-size: .82rem;
    }

    body.candidate-detail-page .candidate-profile-study i,
    body.candidate-detail-page .candidate-contact-list i {
        color: #2464ee;
    }

    body.candidate-detail-page .candidate-contact-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        margin: 18px 0;
    }

    body.candidate-detail-page .candidate-contact-list a,
    body.candidate-detail-page .candidate-contact-list span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #435b89;
        font-size: .79rem;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    body.candidate-detail-page .candidate-contact-list a:hover {
        color: #155bed;
    }

    body.candidate-detail-page .candidate-detail-divider {
        height: 1px;
        margin: 20px 0;
        background: #e7ecfa;
    }

    body.candidate-detail-page .candidate-info-section {
        margin-top: 21px;
    }

    body.candidate-detail-page .candidate-info-section h3,
    body.candidate-detail-page .candidate-detail-panel h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: #173575;
        font-size: .96rem;
        font-weight: 800;
    }

    body.candidate-detail-page .candidate-info-section h3 > span,
    body.candidate-detail-page .candidate-detail-panel h3 > span {
        display: grid;
        width: 33px;
        height: 33px;
        flex: 0 0 33px;
        place-items: center;
        border-radius: 10px;
        color: #2464e8;
        background: #eaf1ff;
    }

    body.candidate-detail-page .candidate-skill-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    body.candidate-detail-page .candidate-skill-list span {
        padding: 7px 12px;
        border: 1px solid #d6e4ff;
        border-radius: 20px;
        color: #1e4eb3;
        background: #edf4ff;
        font-size: .76rem;
        font-weight: 700;
    }

    body.candidate-detail-page .candidate-muted {
        margin: 0;
        color: #7584a4;
        font-size: .82rem;
        font-style: italic;
    }

    body.candidate-detail-page .candidate-letter-content {
        padding: 14px;
        border: 1px solid #e6ecfa;
        border-radius: 12px;
        color: #4f628c;
        background: #f7f9ff;
        font-size: .82rem;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    body.candidate-detail-page .candidate-cv-link {
        display: inline-flex;
        min-height: 42px;
        align-items: center;
        gap: 9px;
        margin-top: 19px;
        padding: 0 16px;
        border: 1px solid #cddcff;
        border-radius: 22px;
        color: #1b58d8;
        background: #f3f7ff;
        font-size: .78rem;
        font-weight: 800;
        text-decoration: none;
    }

    body.candidate-detail-page .candidate-cv-link:hover {
        color: #fff;
        border-color: #1d61ed;
        background: #1d61ed;
    }

    body.candidate-detail-page .candidate-detail-aside {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-left: 24px;
        border-left: 1px solid #e4eafb;
    }

    body.candidate-detail-page .candidate-detail-panel {
        padding: 17px;
        border: 1px solid #e5ebfa;
        border-radius: 15px;
        background: linear-gradient(145deg, #fff, #f8faff);
    }

    body.candidate-detail-page .candidate-detail-panel h3 {
        margin-bottom: 15px;
    }

    body.candidate-detail-page .candidate-detail-panel h4 {
        margin: 0 0 8px;
        color: #1b4eaf;
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    body.candidate-detail-page .candidate-application-date {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin: 0 0 15px;
        color: #7886a5;
        font-size: .72rem;
    }

    body.candidate-detail-page .candidate-application-date i {
        margin-top: 2px;
        color: #4774d6;
    }

    body.candidate-detail-page .candidate-offer-details {
        display: grid;
        gap: 10px;
        margin: 0;
    }

    body.candidate-detail-page .candidate-offer-details div {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #edf0fa;
        font-size: .76rem;
    }

    body.candidate-detail-page .candidate-offer-details div:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    body.candidate-detail-page .candidate-offer-details dt {
        color: #7181a3;
        font-weight: 600;
    }

    body.candidate-detail-page .candidate-offer-details dd {
        margin: 0;
        color: #2a467d;
        font-weight: 700;
        text-align: right;
    }

    body.candidate-detail-page .candidate-status-label {
        margin: 2px 0 9px;
        color: #6b7da3;
        font-size: .76rem;
        font-weight: 600;
    }

    body.candidate-detail-page .candidate-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: .76rem;
        font-weight: 800;
    }

    body.candidate-detail-page .candidate-status.accepted {
        color: #07814d;
        background: #ddf6e9;
    }

    body.candidate-detail-page .candidate-status.refused {
        color: #b52f49;
        background: #ffeaee;
    }

    body.candidate-detail-page .candidate-status.waiting {
        color: #95620c;
        background: #fff2d5;
    }

    body.candidate-detail-page .candidate-decision-actions {
        display: grid;
        gap: 9px;
        margin-top: 16px;
    }

    body.candidate-detail-page .candidate-decision-actions form {
        margin: 0;
    }

    body.candidate-detail-page .candidate-accept-button,
    body.candidate-detail-page .candidate-refuse-button {
        display: flex;
        width: 100%;
        min-height: 42px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 10px;
        font-size: .77rem;
        font-weight: 800;
        transition: background .18s ease, color .18s ease;
    }

    body.candidate-detail-page .candidate-accept-button {
        border: 1px solid #087c52;
        color: #fff;
        background: #078c5b;
    }

    body.candidate-detail-page .candidate-accept-button:hover {
        background: #06784f;
    }

    body.candidate-detail-page .candidate-refuse-button {
        border: 1px solid #f1bdc8;
        color: #b52f49;
        background: #fff;
    }

    body.candidate-detail-page .candidate-refuse-button:hover {
        color: #fff;
        border-color: #c83d54;
        background: #c83d54;
    }

    body.candidate-detail-page .candidate-decision-note {
        margin: 12px 0 0;
        color: #7584a4;
        font-size: .75rem;
        line-height: 1.5;
    }

    body.candidate-detail-page footer {
        position: relative;
        overflow: hidden;
        padding: 30px 0 18px;
        color: #d6e5ff;
        background: linear-gradient(110deg, #08295c, #0d4fae 72%, #1261cd);
    }

    body.candidate-detail-page footer::after {
        position: absolute;
        right: 2%;
        bottom: -31px;
        color: rgba(2, 20, 57, .33);
        content: '\f64f';
        font-family: 'Font Awesome 6 Free';
        font-size: 8rem;
        font-weight: 900;
        pointer-events: none;
    }

    body.candidate-detail-page footer .container {
        position: relative;
        z-index: 1;
    }

    body.candidate-detail-page footer a,
    body.candidate-detail-page footer p,
    body.candidate-detail-page footer .text-sm {
        color: #d6e5ff;
    }

    body.candidate-detail-page footer h4,
    body.candidate-detail-page footer h5 {
        color: #fff;
    }

    body.candidate-detail-page .navbar {
        padding: 10px 0;
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    body.candidate-detail-page .navbar .container {
        width: calc(100% - 250px);
        max-width: none;
        min-height: 60px;
        margin-right: 18px;
        margin-left: 232px;
        padding: 7px 20px;
        border: 1px solid rgba(255, 255, 255, .9);
        border-radius: 22px;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 8px 26px rgba(37, 82, 176, .12);
        backdrop-filter: blur(14px);
    }

    body.candidate-detail-page .navbar-brand {
        display: none;
    }

    body.candidate-detail-page .navbar .navbar-nav.mx-auto {
        margin-right: auto !important;
        margin-left: 0 !important;
    }

    body.candidate-detail-page .navbar .nav-link {
        padding: .65rem .8rem;
        border-radius: 18px;
        color: #203b72 !important;
        font-size: .82rem;
        font-weight: 700;
    }

    body.candidate-detail-page .navbar .nav-link:hover,
    body.candidate-detail-page .navbar .nav-link.active {
        color: #175bed !important;
        background: #edf3ff;
    }

    body.candidate-detail-page .navbar .btn-theme-toggle,
    body.candidate-detail-page .navbar .nav-item.position-relative > .nav-link {
        display: grid;
        width: 40px;
        height: 40px;
        padding: 0;
        place-items: center;
        border: 0;
        border-radius: 50%;
        color: #294b8d;
        background: #fff;
        box-shadow: 0 3px 12px rgba(32, 68, 139, .1);
    }

    body.candidate-detail-page .navbar .nav-item.position-relative > .nav-link:hover {
        color: #175bed !important;
    }

    @media (max-width: 991.98px) {
        body.candidate-detail-page .candidate-sidebar {
            display: none;
        }

        body.candidate-detail-page main,
        body.candidate-detail-page footer {
            margin-left: 0;
        }

        body.candidate-detail-page .navbar-brand {
            display: flex;
        }

        body.candidate-detail-page .navbar .container {
            width: auto;
            max-width: 960px;
            margin: 0 auto;
            padding: 8px 14px;
        }

        body.candidate-detail-page .container.mt-3 {
            width: auto;
            margin: 1rem auto 0 !important;
        }

        body.candidate-detail-page .candidate-detail-hero-inner {
            padding-right: 28px;
            padding-left: 28px;
        }

        body.candidate-detail-page .candidate-detail-content {
            width: calc(100% - 36px);
        }

        body.candidate-detail-page .candidate-detail-card {
            grid-template-columns: 1fr;
            gap: 22px;
        }

        body.candidate-detail-page .candidate-detail-aside {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            padding-top: 20px;
            padding-left: 0;
            border-top: 1px solid #e4eafb;
            border-left: 0;
        }

        body.candidate-detail-page .candidate-detail-aside .candidate-decision-panel {
            align-self: start;
        }

        body.candidate-detail-page footer .container {
            max-width: 960px;
        }
    }

    @media (max-width: 575.98px) {
        body.candidate-detail-page .candidate-detail-hero {
            min-height: 220px;
        }

        body.candidate-detail-page .candidate-detail-hero-inner {
            min-height: 220px;
            padding: 20px 18px 24px;
        }

        body.candidate-detail-page .candidate-detail-hero-art {
            top: 17px;
            right: -48px;
            opacity: .48;
            transform: scale(.75);
        }

        body.candidate-detail-page .candidate-detail-hero-copy {
            max-width: 100%;
        }

        body.candidate-detail-page .candidate-detail-hero-copy h1 {
            max-width: 75%;
            font-size: 1.85rem;
        }

        body.candidate-detail-page .candidate-detail-hero-copy > p:not(.candidate-detail-kicker) {
            max-width: 82%;
            font-size: .8rem;
        }

        body.candidate-detail-page .candidate-detail-content {
            width: calc(100% - 24px);
            margin-top: -12px;
        }

        body.candidate-detail-page .candidate-detail-card {
            gap: 18px;
            padding: 16px;
            border-radius: 16px;
        }

        body.candidate-detail-page .candidate-profile-header {
            align-items: flex-start;
            gap: 13px;
        }

        body.candidate-detail-page .candidate-profile-photo {
            width: 78px;
            height: 78px;
            flex-basis: 78px;
            border-width: 4px;
            font-size: 2rem;
        }

        body.candidate-detail-page .candidate-profile-summary h2 {
            font-size: 1.08rem;
        }

        body.candidate-detail-page .candidate-profile-study {
            align-items: flex-start;
            font-size: .74rem;
        }

        body.candidate-detail-page .candidate-contact-list {
            flex-direction: column;
            gap: 9px;
        }

        body.candidate-detail-page .candidate-detail-aside {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
