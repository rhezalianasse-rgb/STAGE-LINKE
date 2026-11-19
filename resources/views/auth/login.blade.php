@extends('layouts.app')

@section('body-class', 'auth-page')
@section('title', 'Connexion')

@section('content')
<section class="login-screen">
    <div class="login-story">
        <a href="{{ url('/') }}" class="login-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Stage Link">
        </a>
        <div class="login-story-copy">
            <p class="login-eyebrow"><span></span> CONNECT • TRAIN • GROW</p>
            <h1>Votre avenir<br>professionnel<br><span>commence ici.</span></h1>
            <p>Retrouvez vos candidatures et découvrez les opportunités qui vous feront avancer.</p>
        </div>
    </div>

    <div class="login-panel-wrap">
        <div class="login-panel">
            <div class="login-heading">
                <p class="login-mobile-brand">STAGE LINK</p>
                <h2>Connexion</h2>
                <span class="login-heading-rule"></span>
                <p>Ravi de vous revoir ! Connectez-vous à votre compte.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login-field">
                    <label for="email">Adresse email</label>
                    <div class="login-input-wrap">
                        <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Entrez votre adresse email"
                            class="@error('email') is-invalid @enderror"
                        >
                    </div>
                    @error('email')
                        <div class="login-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-field">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="login-input-wrap">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <input
                            type="password"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            required
                            autocomplete="current-password"
                            placeholder="Entrez votre mot de passe"
                            class="@error('mot_de_passe') is-invalid @enderror"
                        >
                        <button class="login-password-toggle" type="button" aria-label="Afficher le mot de passe" aria-pressed="false" data-password-toggle="mot_de_passe">
                            <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('mot_de_passe')
                        <div class="login-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-options">
                    <label class="login-remember" for="remember">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="login-submit">
                    Se connecter <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <div class="login-divider"><span>ou</span></div>
            <a href="{{ route('google.redirect') }}" class="login-google">
                <i class="fa-brands fa-google" aria-hidden="true"></i> Continuer avec Google
            </a>

            <div class="login-secure"><span></span> Connexion sécurisée à votre espace</div>
            <p class="login-signup">Vous n'avez pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
        </div>
    </div>
</section>
@endsection

@section('styles')
@include('auth.partials.styles')
@endsection

@section('scripts')
@include('auth.partials.password-toggle')
@endsection
