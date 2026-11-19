@extends('layouts.app')
@section('title', 'Gestion des Entreprises')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Entreprises</h1>
        <a href="{{ route('admin.entreprises.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter une entreprise
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 fw-bold text-primary">Liste des entreprises inscrites</h6>
            <form action="{{ route('admin.entreprises') }}" method="GET" class="d-flex w-25">
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
                            <th>Nom Entreprise</th>
                            <th>Contact Email</th>
                            <th>Secteur</th>
                            <th>Nb Offres</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises ?? [] as $entreprise)
                        <tr>
                            <td>{{ $entreprise->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($entreprise->logo_path)
                                        <img src="{{ asset('storage/' . $entreprise->logo_path) }}" alt="Logo" class="rounded-circle me-2" width="30" height="30">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-building text-secondary small"></i>
                                        </div>
                                    @endif
                                    {{ $entreprise->nom_entreprise }}
                                </div>
                            </td>
                            <td>{{ $entreprise->user->email ?? '' }}</td>
                            <td>{{ $entreprise->secteur_activite }}</td>
                            <td>{{ $entreprise->offres_count }}</td>
                            <td>
                                @if($entreprise->user && $entreprise->user->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.entreprises.show', $entreprise->id) }}" class="btn btn-sm btn-info text-white" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.entreprises.delete', $entreprise->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise et toutes ses offres ?');">
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
                            <td colspan="7" class="text-center py-4">Aucune entreprise trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                @if(isset($entreprises) && method_exists($entreprises, 'links'))
                    {{ $entreprises->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
