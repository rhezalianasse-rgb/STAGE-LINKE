@extends('layouts.app')
@section('title', 'Gestion des Stagiaires')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Stagiaires</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 fw-bold text-primary">Liste des stagiaires inscrits</h6>
            <form action="{{ route('admin.stagiaires') }}" method="GET" class="d-flex w-25">
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
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Niveau d'étude</th>
                            <th>Domaine</th>
                            <th>Statut</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stagiaires ?? [] as $stagiaire)
                        <tr>
                            <td>{{ $stagiaire->id }}</td>
                            <td>{{ $stagiaire->user->nom ?? '' }} {{ $stagiaire->user->prenom ?? '' }}</td>
                            <td>{{ $stagiaire->user->email ?? '' }}</td>
                            <td>{{ $stagiaire->niveau_etude }}</td>
                            <td>{{ $stagiaire->domaine }}</td>
                            <td>
                                @if($stagiaire->user && $stagiaire->user->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $stagiaire->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.stagiaires.show', $stagiaire->id) }}" class="btn btn-sm btn-info text-white" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.stagiaires.delete', $stagiaire->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce stagiaire ?');">
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
                            <td colspan="8" class="text-center py-4">Aucun stagiaire trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                @if(isset($stagiaires) && method_exists($stagiaires, 'links'))
                    {{ $stagiaires->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
