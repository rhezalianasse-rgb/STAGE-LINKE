@extends('layouts.app')
@section('title', 'Gestion des Candidatures')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Candidatures</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 fw-bold text-primary">Liste de toutes les candidatures</h6>
            <form action="{{ route('admin.candidatures') }}" method="GET" class="d-flex w-25">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Stagiaire</th>
                            <th>Offre</th>
                            <th>Entreprise</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidatures ?? [] as $candidature)
                        <tr>
                            <td>{{ $candidature->id }}</td>
                            <td>
                                <a href="{{ route('admin.stagiaires.show', $candidature->stagiaire_id) }}">
                                    {{ $candidature->stagiaire->user->nom ?? '' }} {{ $candidature->stagiaire->user->prenom ?? '' }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('offres.show', $candidature->offre_id) }}">
                                    {{ Str::limit($candidature->offre->titre ?? 'Offre', 20) }}
                                </a>
                            </td>
                            <td>{{ $candidature->offre->entreprise->nom_entreprise ?? '-' }}</td>
                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                            <td>
                                @switch($candidature->statut)
                                    @case('en_attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('acceptee')
                                        <span class="badge bg-success">Acceptée</span>
                                        @break
                                    @case('refusee')
                                        <span class="badge bg-danger">Refusée</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $candidature->statut }}</span>
                                @endswitch
                            </td>
                            <td>
                                <form action="{{ route('admin.candidatures.delete', $candidature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucune candidature trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                @if(isset($candidatures) && method_exists($candidatures, 'links'))
                    {{ $candidatures->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
