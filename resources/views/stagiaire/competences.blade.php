@extends('layouts.app')

@section('body-class', 'student-content-page student-skills-page')
@section('title', 'Mes Compétences - Stagiaire')

@section('content')
@include('stagiaire.partials.page-hero', [
    'breadcrumb' => 'Gestion des compétences',
    'prefix' => 'Gestion des',
    'accent' => 'Compétences',
    'description' => 'Gérez vos compétences pour améliorer votre profil et augmenter vos chances de trouver un stage.',
    'icon' => 'fa-laptop-code',
])

<div class="student-page-content">
    <div class="student-skill-layout">
        <section class="student-surface student-skill-panel">
            <h2 class="student-surface-heading">
                <span class="student-heading-icon"><i class="fa-solid fa-star" aria-hidden="true"></i></span>
                Mes Compétences
            </h2>

            @if(isset($mesCompetences) && $mesCompetences->count() > 0)
                <div class="student-skill-list">
                    @foreach($mesCompetences as $comp)
                        <span class="student-skill-chip">
                            {{ $comp->nom }}
                            <form action="{{ route('stagiaire.competences.remove', $comp->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Supprimer {{ $comp->nom }}" aria-label="Supprimer {{ $comp->nom }}">
                                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                </button>
                            </form>
                        </span>
                    @endforeach
                </div>
            @else
                <div class="student-empty-state mt-3">
                    <span class="student-empty-art"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                    <p>Vous n'avez pas encore ajouté de compétences à votre profil.</p>
                </div>
            @endif
        </section>

        <section class="student-surface student-add-panel">
            <h2 class="student-surface-heading">
                <span class="student-heading-icon student-add-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
                Ajouter une compétence
            </h2>
            <form action="{{ route('stagiaire.competences.add') }}" method="POST">
                @csrf
                <label for="competence_id" class="student-form-label">Sélectionnez une compétence</label>
                <select class="student-form-control" id="competence_id" name="competence_id" required>
                    <option value="" disabled {{ old('competence_id') ? '' : 'selected' }}>Choisir...</option>
                    @forelse($disponibles as $comp)
                        <option value="{{ $comp->id }}" {{ old('competence_id') == $comp->id ? 'selected' : '' }}>{{ $comp->nom }}</option>
                    @empty
                        <option value="" disabled>Aucune compétence disponible</option>
                    @endforelse
                </select>
                @error('competence_id')
                    <span class="student-form-error">{{ $message }}</span>
                @enderror
                <button type="submit" class="student-add-submit">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter
                </button>
            </form>
        </section>
    </div>
</div>
@endsection

@section('styles')
@include('stagiaire.partials.content-page-styles')
@endsection
