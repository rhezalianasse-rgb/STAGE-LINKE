@extends('layouts.app')
@section('title', 'Modifier l\'Offre')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white pt-4 pb-3">
                    <h4 class="card-title fw-bold mb-0 text-primary">Modifier l'offre de stage</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('entreprise.offres.update', $offre->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="titre" class="form-label fw-bold">Titre de l'offre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $offre->titre) }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description détaillée <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description', $offre->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type_stage" class="form-label fw-bold">Type de stage <span class="text-danger">*</span></label>
                                <select class="form-select @error('type_stage') is-invalid @enderror" id="type_stage" name="type_stage" required>
                                    <option value="" disabled>Sélectionnez...</option>
                                    <option value="PFE" {{ old('type_stage', $offre->type_stage) == 'PFE' ? 'selected' : '' }}>PFE</option>
                                    <option value="Stage d'été" {{ old('type_stage', $offre->type_stage) == "Stage d'été" ? 'selected' : '' }}>Stage d'été</option>
                                    <option value="Stage d'observation" {{ old('type_stage', $offre->type_stage) == "Stage d'observation" ? 'selected' : '' }}>Stage d'observation</option>
                                    <option value="Stage pré-emploi" {{ old('type_stage', $offre->type_stage) == "Stage pré-emploi" ? 'selected' : '' }}>Stage pré-emploi</option>
                                </select>
                                @error('type_stage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="duree" class="form-label fw-bold">Durée (en mois) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duree') is-invalid @enderror" id="duree" name="duree" min="1" max="12" value="{{ old('duree', $offre->duree) }}" required>
                                @error('duree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="localisation" class="form-label fw-bold">Localisation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('localisation') is-invalid @enderror" id="localisation" name="localisation" value="{{ old('localisation', $offre->localisation) }}" required>
                                @error('localisation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date_expiration" class="form-label fw-bold">Date limite de candidature</label>
                            <input type="date" class="form-control @error('date_expiration') is-invalid @enderror" id="date_expiration" name="date_expiration" min="{{ today()->toDateString() }}" value="{{ old('date_expiration', $offre->date_expiration?->toDateString()) }}">
                            @error('date_expiration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Compétences requises</label>
                            <div class="row">
                                @php
                                    $selectedCompetences = old('competences', $offre->competences->pluck('id')->toArray());
                                @endphp
                                @forelse($competences ?? [] as $competence)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="competences[]" value="{{ $competence->id }}" id="comp_{{ $competence->id }}" {{ in_array($competence->id, $selectedCompetences) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="comp_{{ $competence->id }}">
                                                {{ $competence->nom }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted">Aucune compétence disponible.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('entreprise.dashboard' ?? 'home') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
