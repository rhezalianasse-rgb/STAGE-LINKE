@extends('layouts.app')

@section('body-class', 'student-content-page student-favorites-page')
@section('title', 'Mes Favoris - Stagiaire')

@section('content')
@include('stagiaire.partials.page-hero', [
    'breadcrumb' => 'Mes Favoris',
    'prefix' => 'Mes Offres',
    'accent' => 'Favoris',
    'description' => 'Retrouvez ici toutes les offres que vous avez ajoutées en favoris.',
    'icon' => 'fa-heart',
])

<div class="student-page-content">
    @if(!isset($favoris) || $favoris->count() === 0)
        <section class="student-surface student-empty-state">
            <span class="student-empty-art"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
            <h3>Aucun favori pour le moment.</h3>
            <p>Parcourez les offres et ajoutez celles qui vous intéressent à vos favoris.</p>
            <a href="{{ route('offres.index') }}" class="student-empty-cta"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Parcourir les offres</a>
        </section>
    @else
        <div class="student-favorites-grid">
            @foreach($favoris as $favori)
                <article class="student-surface student-favorite-card">
                    <div class="student-favorite-header">
                        <div>
                            <h3>{{ $favori->offre->titre ?? 'Titre' }}</h3>
                            <p><i class="fa-solid fa-building me-1" aria-hidden="true"></i> {{ $favori->offre->entreprise->nom_entreprise ?? 'Entreprise' }}</p>
                        </div>
                        <form action="{{ route('stagiaire.offres.favori', $favori->offre_id) }}" method="POST">
                            @csrf
                            <button type="submit" title="Retirer des favoris" aria-label="Retirer {{ $favori->offre->titre ?? 'cette offre' }} des favoris">
                                <i class="fa-solid fa-heart" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>

                    <div class="student-favorite-details">
                        <span><i class="fa-solid fa-briefcase" aria-hidden="true"></i> {{ $favori->offre->type_stage ?? 'Type non précisé' }}</span>
                        <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $favori->offre->localisation ?? 'Lieu non précisé' }}</span>
                        <span><i class="fa-regular fa-calendar-days" aria-hidden="true"></i> Ajouté le {{ $favori->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="student-favorite-actions">
                        <span>{{ $favori->offre->duree ?? '—' }} mois</span>
                        <a href="{{ route('offres.show', $favori->offre_id) }}"><i class="fa-regular fa-eye" aria-hidden="true"></i> Voir l'offre</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    @if(isset($favoris) && method_exists($favoris, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $favoris->links() }}
        </div>
    @endif
</div>
@endsection

@section('styles')
@include('stagiaire.partials.content-page-styles')
@endsection
