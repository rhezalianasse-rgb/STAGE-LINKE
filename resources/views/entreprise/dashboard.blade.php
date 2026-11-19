@extends('layouts.app')

@section('title', 'Tableau de Bord - Entreprise')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Bienvenue, {{ $entreprise->nom_entreprise ?? Auth::user()->prenom }} !</h2>
        <a href="{{ route('entreprise.offres.create') ?? '#' }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouvelle Offre
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card dashboard-stat dashboard-stat-blue">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Offres actives</p><strong class="dashboard-stat-value">{{ $stats['offres_publiees'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-briefcase"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat dashboard-stat-teal">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Candidatures reçues</p><strong class="dashboard-stat-value">{{ $stats['candidatures_recues'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-inbox"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat dashboard-stat-amber">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">En attente</p><strong class="dashboard-stat-value">{{ $stats['candidatures_attente'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-hourglass-half"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat dashboard-stat-green">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Acceptées</p><strong class="dashboard-stat-value">{{ $stats['candidatures_acceptees'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-check-circle"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Candidatures -->
    <div class="card">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Candidatures Récentes Reçues</h5>
                <a href="{{ route('entreprise.candidatures') ?? '#' }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Stagiaire</th>
                            <th>Offre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCandidatures ?? [] as $candidature)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary rounded-circle text-white d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $candidature->stagiaire->user->prenom ?? '' }} {{ $candidature->stagiaire->user->nom ?? '' }}</h6>
                                        <small class="text-muted">{{ $candidature->stagiaire->domaine ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $candidature->offre->titre ?? 'N/A' }}</td>
                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($candidature->statut == 'acceptee')
                                    <span class="badge bg-success rounded-pill">Acceptée</span>
                                @elseif($candidature->statut == 'refusee')
                                    <span class="badge bg-danger rounded-pill">Refusée</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill">En attente</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('entreprise.candidatures.show', $candidature->id) ?? '#' }}" class="btn btn-sm btn-primary">
                                    Examiner
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Aucune candidature récente reçue.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
