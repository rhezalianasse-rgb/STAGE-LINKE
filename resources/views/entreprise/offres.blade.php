@extends('layouts.app')

@section('title', 'Mes Offres')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mes Offres</h1>
        <a href="{{ route('entreprise.offres.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle offre
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Offre</th>
                    <th>Type</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($offres as $offre)
                    <tr>
                        <td class="fw-semibold">{{ $offre->titre }}</td>
                        <td>{{ $offre->type_stage }}</td>
                        <td>{{ $offre->localisation }}</td>
                        <td>
                            <span class="badge {{ $offre->isExpired() ? 'bg-danger' : ($offre->statut === 'active' ? 'bg-success' : 'bg-secondary') }}">
                                {{ $offre->isExpired() ? 'Expirée' : ($offre->statut === 'active' ? 'Active' : 'Inactive') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('entreprise.offres.edit', $offre->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('entreprise.offres.destroy', $offre->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette offre ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Vous n'avez pas encore publié d'offre.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $offres->links() }}
    </div>
</div>
@endsection