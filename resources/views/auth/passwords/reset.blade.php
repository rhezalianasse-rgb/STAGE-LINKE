@extends('layouts.app')

@section('body-class', 'auth-page')
@section('title', 'Nouveau mot de passe')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 p-md-5">
                <h1 class="h3 fw-bold mb-3">Choisir un nouveau mot de passe</h1>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Nouveau mot de passe</label>
                        <input id="mot_de_passe" name="mot_de_passe" type="password" minlength="12" class="form-control @error('mot_de_passe') is-invalid @enderror" required autocomplete="new-password">
                        <div class="form-text">12 caractères minimum, avec majuscules, minuscules, chiffres et symboles.</div>
                        @error('mot_de_passe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="mot_de_passe_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" type="password" class="form-control" required autocomplete="new-password">
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Enregistrer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
