@extends('layouts.app')
@section('title', 'Détails Entreprise')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil de l'Entreprise</h1>
        <a href="{{ route('admin.entreprises.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <!-- Informations de l'entreprise -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Informations</h6>
                    @if($entreprise->user)
                        <form action="{{ route('admin.users.toggleStatus', $entreprise->user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            @if($entreprise->user->is_active)
                                <button type="submit" class="btn btn-sm btn-danger">Désactiver</button>
                            @else
                                <button type="submit" class="btn btn-sm btn-success">Activer</button>
                            @endif
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($entreprise->logo)
                            <img src="{{ asset('storage/' . $entreprise->logo) }}" alt="Logo" class="rounded-circle mb-3" width="100" height="100">
                        @else
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                <i class="fas fa-building fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <h5 class="fw-bold">{{ $entreprise->nom }}</h5>
                        <p class="text-muted mb-1">{{ $entreprise->user->email ?? '' }}</p>
                        <p class="mb-0">
                            @if($entreprise->user && $entreprise->user->is_active)
                                <span class="badge bg-success">Compte Actif</span>
                            @else
                                <span class="badge bg-danger">Compte Inactif</span>
                            @endif
                        </p>
                    </div>
                    <hr>
                    <p><strong>Secteur :</strong> {{ $entreprise->secteur }}</p>
                    <p><strong>Adresse :</strong> {{ $entreprise->adresse ?? 'N/A' }}</p>
                    <p><strong>Site Web :</strong> 
                        @if($entreprise->site_web)
                            <a href="{{ $entreprise->site_web }}" target="_blank">{{ $entreprise->site_web }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Date d'inscription :</strong> {{ $entreprise->created_at->format('d/m/Y H:i') }}</p>
                    <div class="mt-4 text-center">
                        <p class="h3 fw-bold text-primary">{{ $entreprise->offres->count() ?? 0 }}</p>
                        <p class="text-muted">Offres publiées</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des offres publiées -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Offres publiées</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Candidatures</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entreprise->offres ?? [] as $offre)
                                <tr>
                                    <td>{{ $offre->id }}</td>
                                    <td>{{ $offre->titre }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $offre->type_stage }}</span></td>
                                    <td>{{ $offre->candidatures->count() ?? 0 }}</td>
                                    <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-outline-primary" title="Voir l'offre">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">Cette entreprise n'a pas encore publié d'offres.</td>
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
