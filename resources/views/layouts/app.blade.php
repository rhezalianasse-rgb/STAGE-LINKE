<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stage Link') - Stage Link</title>
    <script>
        (() => {
            let theme;
            try {
                theme = localStorage.getItem('stage-link-theme');
            } catch (error) {
                theme = null;
            }

            if (theme !== 'light' && theme !== 'dark') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --primary-blue: #2563eb;
            --dark-navy: #142b4a;
            --accent-blue: #1d4ed8;
            --bg-light: #f8fafc;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --surface: #ffffff;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-navy);
            font-family: 'Manrope', sans-serif;
            letter-spacing: 0;
        }
        /* Navbar */
        .navbar {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 12px rgba(20, 43, 74, 0.04);
            padding: 0.75rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--dark-navy) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand img {
            height: 44px;
        }
        .nav-link {
            font-weight: 600;
            color: #526176 !important;
            transition: color 0.18s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-blue) !important;
        }
        .nav-link:focus-visible, .navbar-brand:focus-visible, footer a:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.35);
            outline-offset: 3px;
            border-radius: 4px;
        }
        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
        }
        .btn:focus-visible, .form-control:focus-visible, .form-select:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.25);
            outline-offset: 2px;
        }
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }
        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            color: #fff;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(20, 43, 74, 0.045);
        }
        .card-header, .card-footer {
            border-color: var(--border-color);
            background: var(--surface);
        }
        .dashboard-stat {
            height: 100%;
            border-top: 3px solid var(--metric-accent, var(--primary-blue));
        }
        .dashboard-stat .card-body { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 1.35rem; }
        .dashboard-stat-label { margin: 0 0 5px; color: var(--text-muted); font-size: 0.78rem; font-weight: 700; text-transform: uppercase; }
        .dashboard-stat-value { color: var(--dark-navy); font-family: 'Manrope', sans-serif; font-size: 1.8rem; font-weight: 800; line-height: 1.1; }
        .dashboard-stat-icon { display: grid; width: 42px; height: 42px; flex: 0 0 42px; place-items: center; border-radius: 6px; color: var(--metric-accent, var(--primary-blue)); background: var(--metric-tint, #eff6ff); }
        .dashboard-stat-blue { --metric-accent: #2563eb; --metric-tint: #eff6ff; }
        .dashboard-stat-teal { --metric-accent: #0f766e; --metric-tint: #e8f5f3; }
        .dashboard-stat-amber { --metric-accent: #b45309; --metric-tint: #fff7ed; }
        .dashboard-stat-green { --metric-accent: #15803d; --metric-tint: #f0fdf4; }
        .form-label { color: var(--text-primary); font-weight: 600; }
        .form-control, .form-select {
            min-height: 42px;
            border-color: #cbd5e1;
            border-radius: 6px;
            color: var(--text-primary);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .table {
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border-color);
        }
        .table thead th { color: #526176; font-size: 0.82rem; font-weight: 700; }
        .badge { border-radius: 4px; font-weight: 600; }
        .alert { border-radius: 6px; }
        .text-muted { color: var(--text-muted) !important; }
        .bg-primary { background-color: var(--primary-blue) !important; }
        .text-primary { color: var(--primary-blue) !important; }
        .border-primary { border-color: var(--primary-blue) !important; }
        .page-link { color: var(--primary-blue); }
        .page-item.active .page-link { background-color: var(--primary-blue); border-color: var(--primary-blue); }
        .modal-content { border: 1px solid var(--border-color); border-radius: 8px; }
        .progress { background-color: #e8eef6; border-radius: 4px; }
        .progress-bar { background-color: var(--primary-blue); }
        .btn-theme-toggle {
            display: grid;
            width: 40px;
            height: 40px;
            padding: 0;
            place-items: center;
            color: var(--dark-navy);
            border: 1px solid var(--border-color);
            background: var(--surface);
        }
        .btn-theme-toggle:hover { color: var(--primary-blue); border-color: var(--primary-blue); background: var(--surface); }

        html[data-theme="dark"] {
            color-scheme: dark;
            --dark-navy: #dce8f6;
            --bg-light: #0b1220;
            --text-primary: #e5edf7;
            --text-muted: #a3b3c8;
            --border-color: #29384c;
            --surface: #111c2d;
        }
        html[data-theme="dark"] body { background-color: var(--bg-light); color: var(--text-primary); }
        html[data-theme="dark"] .navbar { background: #101a2a; border-color: #29384c; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2); }
        html[data-theme="dark"] .navbar-toggler { border-color: #526176; }
        html[data-theme="dark"] .navbar-toggler-icon { filter: invert(1); }
        html[data-theme="dark"] .nav-link { color: #c3d0e0 !important; }
        html[data-theme="dark"] .nav-link:hover, html[data-theme="dark"] .nav-link.active { color: #8bb5ff !important; }
        html[data-theme="dark"] .btn-theme-toggle { color: #e5edf7; border-color: #34465d; background: #172438; }
        html[data-theme="dark"] .btn-theme-toggle:hover { color: #bfdbfe; border-color: #60a5fa; background: #172438; }
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .card-header,
        html[data-theme="dark"] .card-footer,
        html[data-theme="dark"] .dropdown-menu,
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .list-group-item { color: var(--text-primary); border-color: var(--border-color); background-color: var(--surface); }
        html[data-theme="dark"] .dropdown-item { color: #d3deeb; }
        html[data-theme="dark"] .dropdown-item:hover { color: #fff; background: #203149; }
        html[data-theme="dark"] .dropdown-divider { border-color: var(--border-color); }
        html[data-theme="dark"] .bg-white { background-color: var(--surface) !important; }
        html[data-theme="dark"] .bg-light { background-color: #18263a !important; }
        html[data-theme="dark"] .text-dark { color: var(--text-primary) !important; }
        html[data-theme="dark"] .text-muted { color: var(--text-muted) !important; }
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .input-group-text {
            color: var(--text-primary);
            border-color: #3a4b61;
            background-color: #0e1929;
        }
        html[data-theme="dark"] .form-control::placeholder { color: #8494a9; }
        html[data-theme="dark"] .form-control:focus,
        html[data-theme="dark"] .form-select:focus { color: #f8fafc; background-color: #101d30; }
        html[data-theme="dark"] .table {
            --bs-table-color: var(--text-primary);
            --bs-table-bg: var(--surface);
            --bs-table-border-color: var(--border-color);
            --bs-table-striped-bg: #162439;
            --bs-table-hover-bg: #1a2a40;
        }
        html[data-theme="dark"] .table-light {
            --bs-table-color: #d9e4f1;
            --bs-table-bg: #1a293d;
            --bs-table-border-color: var(--border-color);
        }
        html[data-theme="dark"] .table > :not(caption) > * > * { color: var(--bs-table-color); background-color: var(--bs-table-bg); }
        html[data-theme="dark"] .table-hover > tbody > tr:hover > * { color: #f8fafc; background-color: var(--bs-table-hover-bg); }
        html[data-theme="dark"] .page-link { color: #bfdbfe; border-color: var(--border-color); background: var(--surface); }
        html[data-theme="dark"] .page-item.active .page-link { color: #fff; background: var(--primary-blue); }
        html[data-theme="dark"] .alert-light { color: var(--text-primary); border-color: var(--border-color); background: #172438; }
        html[data-theme="dark"] .progress { background-color: #28384c; }
        html[data-theme="dark"] footer { background-color: #080f1b; }

        html[data-theme="dark"] body.home-page {
            --home-navy: #e3edf9;
            --home-blue: #8bb5ff;
            --home-bg: #0b1220;
            --home-ink: #e5edf7;
            --home-muted: #a3b3c8;
            --home-line: #29384c;
            --home-teal: #5eead4;
            background: var(--home-bg);
            color: var(--home-ink);
        }
        html[data-theme="dark"] body.home-page .navbar { background: #101a2a; border-color: var(--home-line); }
        html[data-theme="dark"] body.home-page .home-hero {
            background-color: var(--home-bg);
            background-image: linear-gradient(to right, rgba(226, 237, 249, 0.035) 1px, transparent 1px), linear-gradient(to bottom, rgba(226, 237, 249, 0.035) 1px, transparent 1px);
        }
        html[data-theme="dark"] body.home-page .home-search,
        html[data-theme="dark"] body.home-page .home-photo-caption,
        html[data-theme="dark"] body.home-page .home-proof,
        html[data-theme="dark"] body.home-page .home-steps { color: var(--home-ink); border-color: var(--home-line); background: #111c2d; }
        html[data-theme="dark"] body.home-page .home-search input { color: var(--home-ink); background: transparent; }
        html[data-theme="dark"] body.home-page .home-search input::placeholder { color: #93a4ba; }
        html[data-theme="dark"] body.home-page .home-photo-wrap::before { border-color: #34465d; }
        html[data-theme="dark"] body.home-page .home-photo-caption strong { color: var(--home-ink); }
        html[data-theme="dark"] body.home-page .home-step { border-color: var(--home-line); }
        html[data-theme="dark"] body.home-page .home-step-number { color: #93a4ba; }
        html[data-theme="dark"] body.home-page .home-cta-band { background: #070d17; }
        html[data-theme="dark"] body.home-page footer { background: #070d17; }

        @media (max-width: 575.98px) {
            .navbar-brand img { height: 38px; }
            .navbar .navbar-nav { padding-top: 0.35rem; }
            .navbar .navbar-nav.ms-auto { align-items: stretch !important; }
            .dashboard-stat .card-body { padding: 1rem; }
            .dashboard-stat-value { font-size: 1.5rem; }
        }
        /* Badges */
        .notification-badge {
            background-color: #ef4444;
            color: white;
            font-size: 0.7rem;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
        }
        .nav-item.position-relative {
            margin-right: 15px;
        }
        /* Footer */
        footer {
            margin-top: auto;
            background-color: var(--dark-navy);
            color: #cbd5e1;
            padding: 3rem 0 1.5rem;
        }
        footer a {
            color: #cbd5e1;
            text-decoration: none;
        }
        footer a:hover {
            color: white;
        }
        main {
            flex-grow: 1;
        }
    </style>
    @yield('styles')
</head>
<body class="@yield('body-class')">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Stage Link Logo" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @auth
                        @if(auth()->user()->role == 'stagiaire')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('stagiaire.dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('offres.index') ? 'active' : '' }}" href="{{ url('/offres') }}">Offres</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('stagiaire.candidatures') ? 'active' : '' }}" href="{{ route('stagiaire.candidatures') }}">Mes Candidatures</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('stagiaire.favoris') ? 'active' : '' }}" href="{{ route('stagiaire.favoris') }}">Mes Favoris</a></li>
                        @elseif(auth()->user()->role == 'entreprise')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('entreprise.dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('entreprise.offres.*') ? 'active' : '' }}" href="{{ route('entreprise.offres.index') }}">Mes Offres</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('entreprise.candidatures*') ? 'active' : '' }}" href="{{ route('entreprise.candidatures') }}">Candidatures Reçues</a></li>
                        @elseif(auth()->user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/stagiaires') }}">Stagiaires</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/entreprises') }}">Entreprises</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/offres') }}">Offres</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/candidatures') }}">Candidatures</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/specialites') }}">Spécialités</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/competences') }}">Compétences</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ url('/offres') }}">Offres de stage</a></li>
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-2">
                        <button class="btn btn-theme-toggle" type="button" data-theme-toggle aria-label="Activer le mode sombre" title="Activer le mode sombre" aria-pressed="false">
                            <i class="fa-solid fa-moon" data-theme-icon aria-hidden="true"></i>
                        </button>
                    </li>
                    @auth
                        @if(in_array(auth()->user()->role, ['stagiaire', 'entreprise']))
                            <li class="nav-item position-relative me-3">
                                <a class="nav-link" href="{{ url('/notifications') }}">
                                    <i class="fa-regular fa-bell fs-5"></i>
                                    @php $unreadCount = auth()->user()->unreadNotifications->count() ?? 0; @endphp
                                    @if($unreadCount > 0)
                                        <span class="notification-badge">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                @php
                                    $profileImagePath = match (auth()->user()->role) {
                                        'stagiaire' => auth()->user()->stagiaire?->photo_path,
                                        'entreprise' => auth()->user()->entreprise?->logo_path,
                                        default => null,
                                    };
                                    $profileImage = $profileImagePath
                                        ? (auth()->user()->role === 'stagiaire'
                                            ? route('profile-photos.show', ['filename' => basename($profileImagePath)])
                                            : asset('storage/' . $profileImagePath))
                                        : null;
                                @endphp
                                @if($profileImage)
                                    <img src="{{ $profileImage }}" alt="Photo de profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        {{ substr(auth()->user()->prenom ?? auth()->user()->nom ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom ?? 'User' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(in_array(auth()->user()->role, ['stagiaire', 'entreprise']))
                                    <li><a class="dropdown-item" href="{{ url('/profil') }}"><i class="fa-regular fa-user me-2"></i>Mon Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                @if(! auth()->user()->google_id)
                                    <li>
                                        <form method="POST" action="{{ route('google.link') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i class="fa-brands fa-google me-2"></i>Lier Google</button>
                                        </form>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-primary" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="status">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Stage Link Logo" style="height: 35px;" onerror="this.style.display='none'">
                        <h4 class="mb-0 text-white font-weight-bold">STAGE LINK</h4>
                    </div>
                    <p class="text-sm">La plateforme numéro 1 pour connecter les étudiants talentueux aux entreprises innovantes.</p>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-3">Liens Utiles</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('offres.index') }}">Offres de stage</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}">Créer un compte</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}">Connexion</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-3">Légal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">Conditions d'utilisation</li>
                        <li class="mb-2">Politique de confidentialité</li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="text-white mb-3">Suivez-nous</h5>
                    <div class="d-flex gap-3 fs-5">
                        <i class="fa-brands fa-linkedin" aria-hidden="true"></i>
                        <i class="fa-brands fa-twitter" aria-hidden="true"></i>
                        <i class="fa-brands fa-facebook" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3 border-secondary">
            <div class="text-center text-sm">
                &copy; {{ date('Y') }} Stage Link. Tous droits réservés.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const themeButtons = document.querySelectorAll('[data-theme-toggle]');
            const root = document.documentElement;

            if (!themeButtons.length) return;

            const updateThemeButtons = () => {
                const isDark = root.dataset.theme === 'dark';
                const action = isDark ? 'Activer le mode clair' : 'Activer le mode sombre';
                themeButtons.forEach((themeButton) => {
                    const themeIcon = themeButton.querySelector('[data-theme-icon]');
                    if (themeIcon) {
                        themeIcon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                    }
                    themeButton.setAttribute('aria-label', action);
                    themeButton.setAttribute('title', action);
                    themeButton.setAttribute('aria-pressed', String(isDark));
                });
            };

            updateThemeButtons();
            themeButtons.forEach((themeButton) => {
                themeButton.addEventListener('click', () => {
                    root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
                    try {
                        localStorage.setItem('stage-link-theme', root.dataset.theme);
                    } catch (error) {
                        // The selected theme remains active for this page view.
                    }
                    updateThemeButtons();
                });
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>
