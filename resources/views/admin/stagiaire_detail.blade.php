@extends('layouts.app')
@section('title', 'Détails Stagiaire')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil du Stagiaire</h1>
        <a href="{{ route('admin.stagiaires') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Informations</h6>
                    @if($stagiaire->user)
                        <form action="{{ route('admin.users.toggleStatus', $stagiaire->user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            @if($stagiaire->user->statut === 'actif')
                                <button type="submit" class="btn btn-sm btn-danger">Désactiver</button>
                            @else
                                <button type="submit" class="btn btn-sm btn-success">Activer</button>
                            @endif
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($stagiaire->photo_path)
                            <img src="{{ route('profile-photos.show', ['filename' => basename($stagiaire->photo_path)]) }}" alt="Photo de {{ $stagiaire->user->prenom ?? 'profil' }}" class="rounded-circle d-inline-block mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <h5 class="fw-bold">{{ $stagiaire->user->nom ?? '' }} {{ $stagiaire->user->prenom ?? '' }}</h5>
                        <p class="text-muted mb-1">{{ $stagiaire->user->email ?? '' }}</p>
                        <p class="mb-0">
                            @if($stagiaire->user && $stagiaire->user->statut === 'actif')
                                <span class="badge bg-success">Compte Actif</span>
                            @else
                                <span class="badge bg-danger">Compte Inactif</span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <p><strong>Niveau d'étude :</strong> {{ $stagiaire->niveau_etude }}</p>
                    <p><strong>Domaine :</strong> {{ $stagiaire->domaine }}</p>
                    <p><strong>Date d'inscription :</strong> {{ $stagiaire->created_at->format('d/m/Y H:i') }}</p>
                    
                    @if($stagiaire->cv_path)
                        <div class="d-grid mt-3">
                            <a href="{{ route('admin.stagiaires.cv', $stagiaire->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-file-pdf"></i> Voir le CV
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Compétences -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Compétences</h6>
                </div>
                <div class="card-body">
                    @forelse($stagiaire->competences ?? [] as $competence)
                        <span class="badge bg-secondary me-1 mb-1 p-2">{{ $competence->nom }}</span>
                    @empty
                        <p class="text-muted mb-0">Aucune compétence renseignée.</p>
                    @endforelse
                </div>
            </div>

            <!-- Historique des candidatures -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Candidatures ({{ $stagiaire->candidatures->count() ?? 0 }})</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Offre</th>
                                    <th>Entreprise</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stagiaire->candidatures ?? [] as $candidature)
                                <tr>
                                    <td>
                                        <a href="{{ route('offres.show', $candidature->offre_id) }}">
                                            {{ $candidature->offre->titre ?? 'Offre introuvable' }}
                                        </a>
                                    </td>
                                    <td>{{ $candidature->offre->entreprise->nom_entreprise ?? '-' }}</td>
                                    <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($candidature->statut)
                                            @case('en attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                                @break
                                            @case('acceptée')
                                                <span class="badge bg-success">Acceptée</span>
                                                @break
                                            @case('refusée')
                                                <span class="badge bg-danger">Refusée</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $candidature->statut }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Aucune candidature effectuée.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
