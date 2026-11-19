@extends('layouts.app')

@section('body-class', 'auth-page')
@section('title', 'Mot de passe oublié')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 p-md-5">
                <h1 class="h3 fw-bold mb-3">Réinitialiser le mot de passe</h1>
                <p class="text-muted">Saisissez l'adresse associée à votre compte. Si elle est enregistrée, vous recevrez un lien de réinitialisation.</p>

                @if(session('status'))
                    <div class="alert alert-info" role="status">{{ session('status') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Envoyer le lien</button>
                </form>

                <a class="text-center mt-3" href="{{ route('login') }}">Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>
@endsection
