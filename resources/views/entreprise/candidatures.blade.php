@extends('layouts.app')

@section('title', 'Gestion des Candidatures - Entreprise')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Gestion des Candidatures</h2>

    <div class="card">
        <div class="card-body p-4">
            
            <!-- Filters -->
            <ul class="nav nav-tabs mb-4" id="candidatureTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('statut') == '' ? 'active' : '' }}" href="{{ route('entreprise.candidatures') }}">Toutes</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-warning {{ request('statut') == 'en_attente' ? 'active fw-bold' : '' }}" href="{{ route('entreprise.candidatures', ['statut' => 'en_attente']) }}">En attente</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-success {{ request('statut') == 'acceptee' ? 'active fw-bold' : '' }}" href="{{ route('entreprise.candidatures', ['statut' => 'acceptee']) }}">Acceptées</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-danger {{ request('statut') == 'refusee' ? 'active fw-bold' : '' }}" href="{{ route('entreprise.candidatures', ['statut' => 'refusee']) }}">Refusées</a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Stagiaire</th>
                            <th>Offre</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidatures ?? [] as $candidature)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $candidature->stagiaire->user->prenom ?? '' }} {{ $candidature->stagiaire->user->nom ?? '' }}</div>
                                <small class="text-muted">{{ $candidature->stagiaire->niveau_etude ?? '' }} - {{ $candidature->stagiaire->domaine ?? '' }}</small>
                            </td>
                            <td>{{ $candidature->offre->titre ?? 'Offre inconnue' }}</td>
                            <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($candidature->statut == 'acceptee')
                                    <span class="badge bg-success rounded-pill px-3 py-2">Acceptée</span>
                                @elseif($candidature->statut == 'refusee')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Refusée</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">En attente</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('entreprise.candidatures.show', $candidature->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                                Aucune candidature trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($candidatures) && method_exists($candidatures, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $candidatures->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
