@extends('layouts.app')

@section('title', 'Ajouter une entreprise')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ajouter une entreprise</h1>
        <a href="{{ route('admin.entreprises') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <form action="{{ route('admin.entreprises.store') }}" method="POST" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Entreprise</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nom_entreprise" class="form-label">Nom de l'entreprise</label>
                    <input id="nom_entreprise" name="nom_entreprise" class="form-control @error('nom_entreprise') is-invalid @enderror" value="{{ old('nom_entreprise') }}" required>
                    @error('nom_entreprise') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="secteur_activite" class="form-label">Secteur d'activité</label>
                    <input id="secteur_activite" name="secteur_activite" class="form-control @error('secteur_activite') is-invalid @enderror" value="{{ old('secteur_activite') }}">
                    @error('secteur_activite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input id="adresse" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse') }}">
                    @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="site_web" class="form-label">Site web</label>
                    <input id="site_web" name="site_web" type="url" class="form-control @error('site_web') is-invalid @enderror" value="{{ old('site_web') }}" placeholder="https://exemple.com">
                    @error('site_web') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h2 class="h5 mt-4 mb-3">Compte de contact</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" required>
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Adresse email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input id="telephone" name="telephone" type="tel" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}">
                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="mot_de_passe" class="form-label">Mot de passe initial</label>
                    <input id="mot_de_passe" name="mot_de_passe" type="password" minlength="12" class="form-control @error('mot_de_passe') is-invalid @enderror" required>
                    @error('mot_de_passe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">12 caractères minimum, avec majuscules, minuscules, chiffres et symboles.</div>
                </div>
                <div class="col-md-6">
                    <label for="mot_de_passe_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" type="password" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end p-3">
            <button type="submit" class="btn btn-primary">Créer le compte entreprise</button>
        </div>
    </form>
</div>
@endsection