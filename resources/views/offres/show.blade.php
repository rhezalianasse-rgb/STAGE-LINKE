@extends('layouts.app')
@section('title', 'Détails de l\'Offre')
@section('body-class', 'offer-detail-page')
@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($offre->entreprise && $offre->entreprise->logo_path)
                            <img src="{{ asset('storage/' . $offre->entreprise->logo_path) }}" alt="Logo de {{ $offre->entreprise->nom_entreprise }}" class="rounded-circle me-3" width="64" height="64" style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                                <i class="fas fa-building fa-2x text-secondary"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="h3 mb-1 fw-bold">{{ $offre->titre }}</h1>
                            <h5 class="text-muted">{{ $offre->entreprise->nom_entreprise ?? 'Entreprise inconnue' }}</h5>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary p-2"><i class="fas fa-briefcase"></i> {{ $offre->type_stage }}</span>
                        <span class="badge bg-info p-2"><i class="far fa-clock"></i> {{ $offre->duree }} mois</span>
                        <span class="badge bg-secondary p-2"><i class="fas fa-map-marker-alt"></i> {{ $offre->localisation }}</span>
                        <span class="badge bg-light text-dark p-2 border"><i class="far fa-calendar-alt"></i> Publié le {{ $offre->created_at->format('d/m/Y') }}</span>
                        @if($offre->date_expiration)
                            <span class="badge bg-light text-dark p-2 border">Candidatures jusqu'au {{ $offre->date_expiration->format('d/m/Y') }}</span>
                        @endif
                    </div>

                    <h4 class="mb-3">Description</h4>
                    <div class="mb-4 text-break">
                        {!! nl2br(e($offre->description)) !!}
                    </div>

                    <h4 class="mb-3">Compétences requises</h4>
                    <div class="mb-4">
                        @forelse($offre->competences ?? [] as $competence)
                            <span class="badge bg-secondary me-1 mb-1 p-2">{{ $competence->nom }}</span>
                        @empty
                            <span class="text-muted">Aucune compétence spécifique indiquée.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="card-title fw-bold mb-0">À propos de l'entreprise</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nom :</strong> {{ $offre->entreprise->nom_entreprise ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Secteur :</strong> {{ $offre->entreprise->secteur_activite ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Adresse :</strong> {{ $offre->entreprise->adresse ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Site Web :</strong> <a href="{{ $offre->entreprise->site_web ?? '#' }}" target="_blank">{{ $offre->entreprise->site_web ?? 'N/A' }}</a></p>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'stagiaire')
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Matching -->
                        <h6 class="fw-bold mb-2">Correspondance du profil</h6>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $matchingRate }}%;" aria-valuenow="{{ $matchingRate }}" aria-valuemin="0" aria-valuemax="100">{{ round($matchingRate) }}%</div>
                        </div>

                        <!-- Actions -->
                        @php
                        @if($hasApplied)
                            <div class="alert alert-info text-center mb-0">
                                <strong>Statut :</strong> <span class="badge bg-warning text-dark">En attente</span>
                            </div>
                        @else
                            <button type="button" class="btn btn-primary w-100 mb-2 fw-bold" data-bs-toggle="modal" data-bs-target="#postulerModal">
                                Postuler
                            </button>
                        @endif

                        <form action="{{ route('stagiaire.offres.favori', $offre->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $isFavori ? 'btn-danger' : 'btn-outline-danger' }} w-100">
                                <i class="{{ $isFavori ? 'fas' : 'far' }} fa-heart"></i>
                                {{ $isFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Postuler -->
@if(auth()->check() && auth()->user()->role === 'stagiaire')
<div class="modal fade" id="postulerModal" tabindex="-1" aria-labelledby="postulerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('stagiaire.offres.postuler', $offre->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postulerModalLabel">Postuler à : {{ $offre->titre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lettre_motivation" class="form-label">Lettre de motivation</label>
                        <textarea class="form-control" id="lettre_motivation" name="lettre_motivation" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV (Optionnel - pdf, doc, docx)</label>
                        <input class="form-control" type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
                        <div class="form-text">Si non fourni, votre CV par défaut sera utilisé.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer la candidature</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
