<aside class="dashboard-sidebar">
    <a href="{{ route('home') }}" class="dashboard-brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <span>STAGE LINK</span>
    </a>

    <nav class="dashboard-nav" aria-label="Navigation principale">
        <a class="dashboard-nav-link {{ request()->routeIs('stagiaire.dashboard') ? 'active' : '' }}" href="{{ route('stagiaire.dashboard') }}" @if(request()->routeIs('stagiaire.dashboard')) aria-current="page" @endif>
            <i class="fa-solid fa-grip" aria-hidden="true"></i><span>Tableau de bord</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('offres.index') ? 'active' : '' }}" href="{{ route('offres.index') }}" @if(request()->routeIs('offres.index')) aria-current="page" @endif>
            <i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Offres</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('stagiaire.candidatures') ? 'active' : '' }}" href="{{ route('stagiaire.candidatures') }}" @if(request()->routeIs('stagiaire.candidatures')) aria-current="page" @endif>
            <i class="fa-solid fa-people-group" aria-hidden="true"></i><span>Candidatures</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('stagiaire.favoris') ? 'active' : '' }}" href="{{ route('stagiaire.favoris') }}" @if(request()->routeIs('stagiaire.favoris')) aria-current="page" @endif>
            <i class="fa-solid fa-heart" aria-hidden="true"></i><span>Mes favoris</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('stagiaire.profil') ? 'active' : '' }}" href="{{ route('stagiaire.profil') }}" @if(request()->routeIs('stagiaire.profil')) aria-current="page" @endif>
            <i class="fa-solid fa-user" aria-hidden="true"></i><span>Profil</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('stagiaire.competences') ? 'active' : '' }}" href="{{ route('stagiaire.competences') }}" @if(request()->routeIs('stagiaire.competences')) aria-current="page" @endif>
            <i class="fa-solid fa-gear" aria-hidden="true"></i><span>Compétences</span>
        </a>
    </nav>

    <div class="dashboard-sidebar-footer">
        <span class="dashboard-cap"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
        <p>Construisez<br>votre avenir<br>avec <strong>Stage Link</strong></p>
    </div>

    <form class="dashboard-logout" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i><span>Déconnexion</span></button>
    </form>
</aside>
