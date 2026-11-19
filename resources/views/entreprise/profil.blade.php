@extends('layouts.app')

@section('title', 'Profil de l\'Entreprise')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Profil de l'Entreprise</h2>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('entreprise.profil.update') ?? '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-4 text-center mb-4">
                        <div class="mb-3">
                            @if(isset($entreprise) && $entreprise->logo_path)
                                <img src="{{ asset('storage/' . $entreprise->logo_path) }}" alt="Logo Entreprise" class="img-thumbnail rounded mb-3" style="width: 200px; height: 200px; object-fit: contain;">
                            @else
                                <div class="bg-light rounded border d-inline-flex justify-content-center align-items-center mb-3 text-secondary" style="width: 200px; height: 200px;">
                                    <i class="fas fa-building fa-5x"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="logo" class="form-label">Logo de l'entreprise</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom de l'entreprise</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $entreprise->nom_entreprise ?? '') }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="secteur_activite" class="form-label">Secteur d'activité</label>
                                <input type="text" class="form-control @error('secteur_activite') is-invalid @enderror" id="secteur_activite" name="secteur_activite" value="{{ old('secteur_activite', $entreprise->secteur_activite ?? '') }}" required>
                                @error('secteur_activite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email de contact</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->email ?? '' }}" readonly disabled>
                            </div>

                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $entreprise->telephone ?? '') }}">
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="site_web" class="form-label">Site Web</label>
                                <input type="url" class="form-control @error('site_web') is-invalid @enderror" id="site_web" name="site_web" value="{{ old('site_web', $entreprise->site_web ?? '') }}" placeholder="https://www.exemple.com">
                                @error('site_web') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="adresse" class="form-label">Adresse complète</label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse', $entreprise->adresse ?? '') }}">
                                @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description de l'entreprise</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $entreprise->description ?? '') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i> Enregistrer le profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
