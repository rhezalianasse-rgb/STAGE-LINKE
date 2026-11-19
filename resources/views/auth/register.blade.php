@extends('layouts.app')

@section('body-class', 'auth-page')
@section('title', 'Inscription')

@section('content')
<section class="login-screen">
    <div class="login-story">
        <a href="{{ url('/') }}" class="login-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Stage Link">
        </a>
        <div class="login-story-copy">
            <p class="login-eyebrow"><span></span> CONNECT • TRAIN • GROW</p>
            <h1>Votre avenir<br>professionnel<br><span>commence ici.</span></h1>
            <p>Créez votre profil et accédez aux opportunités de stage adaptées à votre parcours.</p>
        </div>
    </div>

    <div class="login-panel-wrap">
        <div class="login-panel">
            <div class="login-heading">
                <p class="login-mobile-brand">STAGE LINK</p>
                <h2>Créer un compte</h2>
                <span class="login-heading-rule"></span>
                <p>Rejoignez Stage Link et préparez la suite de votre parcours.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="register-fields-grid">
                    <div class="login-field">
                        <label for="prenom">Prénom</label>
                        <div class="login-input-wrap">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name" placeholder="Votre prénom" class="@error('prenom') is-invalid @enderror">
                        </div>
                        @error('prenom')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="nom">Nom</label>
                        <div class="login-input-wrap">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required autocomplete="family-name" placeholder="Votre nom" class="@error('nom') is-invalid @enderror">
                        </div>
                        @error('nom')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="email">Adresse email</label>
                        <div class="login-input-wrap">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nom@exemple.com" class="@error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="telephone">Téléphone <span class="register-optional">(facultatif)</span></label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" autocomplete="tel" placeholder="06 12 34 56 78" class="@error('telephone') is-invalid @enderror">
                        </div>
                        @error('telephone')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="mot_de_passe">Mot de passe</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="12" autocomplete="new-password" placeholder="12 caractères minimum" class="@error('mot_de_passe') is-invalid @enderror">
                            <button class="login-password-toggle" type="button" aria-label="Afficher le mot de passe" aria-pressed="false" data-password-toggle="mot_de_passe">
                                <i class="fa-regular fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('mot_de_passe')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                        <p class="login-password-hint">12 caractères minimum, avec majuscules, minuscules, chiffres et symboles.</p>
                    </div>

                    <div class="login-field">
                        <label for="mot_de_passe_confirmation">Confirmer le mot de passe</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required autocomplete="new-password" placeholder="Confirmez votre mot de passe">
                            <button class="login-password-toggle" type="button" aria-label="Afficher le mot de passe" aria-pressed="false" data-password-toggle="mot_de_passe_confirmation">
                                <i class="fa-regular fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="login-submit register-submit">
                    Créer mon compte <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <div class="login-divider"><span>ou</span></div>
            <a href="{{ route('google.redirect') }}" class="login-google">
                <i class="fa-brands fa-google" aria-hidden="true"></i> S’inscrire avec Google
            </a>

            <div class="login-secure"><span></span> Vos informations restent protégées</div>
            <p class="login-signup">Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
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
