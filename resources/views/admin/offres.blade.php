@extends('layouts.app')
@section('title', 'Gestion des Offres')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Offres</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 fw-bold text-primary">Liste de toutes les offres</h6>
            <form action="{{ route('admin.offres') }}" method="GET" class="d-flex w-25">
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
                            <th>Titre</th>
                            <th>Entreprise</th>
                            <th>Type</th>
                            <th>Localisation</th>
                            <th>Nb Candidatures</th>
                            <th>Date publication</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offres ?? [] as $offre)
                        <tr>
                            <td>{{ $offre->id }}</td>
                            <td>{{ Str::limit($offre->titre, 30) }}</td>
                            <td>{{ $offre->entreprise->nom_entreprise ?? '-' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $offre->type_stage }}</span></td>
                            <td>{{ $offre->localisation }}</td>
                            <td>{{ $offre->candidatures_count }}</td>
                            <td>{{ $offre->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($offre->isExpired())
                                    <span class="badge bg-danger">Expirée</span>
                                @elseif($offre->statut === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if(!$offre->isExpired() && $offre->statut === 'active')
                                    <a href="{{ route('offres.show', $offre->id) }}" class="btn btn-sm btn-info text-white" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.offres.delete', $offre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
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
                            <td colspan="9" class="text-center py-4">Aucune offre trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                @if(isset($offres) && method_exists($offres, 'links'))
                    {{ $offres->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
