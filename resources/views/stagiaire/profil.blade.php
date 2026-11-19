@extends('layouts.app')

@section('body-class', 'student-content-page student-profile-page')
@section('title', 'Mon Profil - Stagiaire')

@section('content')
@include('stagiaire.partials.page-hero', [
    'breadcrumb' => 'Mon Profil',
    'prefix' => 'Mon',
    'accent' => 'Profil',
    'description' => 'Gérez vos informations personnelles et mettez à jour votre profil.',
    'icon' => 'fa-user-gear',
])

<div class="student-page-content">
    @if(session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @if($errors->has('email'))
        <div class="alert alert-danger" role="alert">{{ $errors->first('email') }}</div>
    @endif
    <section class="student-surface student-profile-card">
        <form action="{{ route('stagiaire.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="student-profile-grid">
                <div>
                    <div class="student-profile-photo">
                        @if(isset($stagiaire) && $stagiaire->photo_path)
                            <img src="{{ route('profile-photos.show', ['filename' => basename($stagiaire->photo_path)]) }}" alt="Photo de profil">
                        @else
                            <span class="student-profile-placeholder"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                        @endif
                        <label for="photo">Photo de profil</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                        @error('photo') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nom" class="student-form-label"><i class="fa-solid fa-user me-2 text-primary" aria-hidden="true"></i>Nom</label>
                        <input type="text" class="student-form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $stagiaire->user->nom ?? '') }}" required>
                        @error('nom') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="student-form-label"><i class="fa-solid fa-user me-2 text-primary" aria-hidden="true"></i>Prénom</label>
                        <input type="text" class="student-form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom', $stagiaire->user->prenom ?? '') }}" required>
                        @error('prenom') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="student-form-label"><i class="fa-regular fa-envelope me-2 text-primary" aria-hidden="true"></i>Email</label>
                        <input type="email" class="student-form-control" id="email" value="{{ Auth::user()->email ?? '' }}" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="student-form-label"><i class="fa-solid fa-phone me-2 text-primary" aria-hidden="true"></i>Téléphone</label>
                        <input type="text" class="student-form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $stagiaire->user->telephone ?? '') }}">
                        @error('telephone') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-3">
                        <label for="date_naissance" class="student-form-label"><i class="fa-regular fa-calendar-days me-2 text-primary" aria-hidden="true"></i>Date de naissance</label>
                        <input type="date" class="student-form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $stagiaire->date_naissance ?? '') }}">
                        @error('date_naissance') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="student-form-label"><i class="fa-solid fa-location-dot me-2 text-primary" aria-hidden="true"></i>Adresse</label>
                        <input type="text" class="student-form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse', $stagiaire->adresse ?? '') }}" placeholder="Votre adresse...">
                        @error('adresse') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="niveau_etude" class="student-form-label"><i class="fa-solid fa-graduation-cap me-2 text-primary" aria-hidden="true"></i>Niveau d'étude</label>
                        <select class="student-form-control @error('niveau_etude') is-invalid @enderror" id="niveau_etude" name="niveau_etude">
                            <option value="">Sélectionner</option>
                            @foreach(['Bac', 'Bac+1', 'Bac+2', 'Bac+3', 'Bac+4', 'Bac+5', 'Doctorat'] as $niveau)
                                <option value="{{ $niveau }}" {{ old('niveau_etude', $stagiaire->niveau_etude ?? '') == $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                            @endforeach
                        </select>
                        @error('niveau_etude') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="domaine" class="student-form-label"><i class="fa-solid fa-book-open me-2 text-primary" aria-hidden="true"></i>Domaine d'étude</label>
                        <input type="text" class="student-form-control @error('domaine') is-invalid @enderror" id="domaine" name="domaine" value="{{ old('domaine', $stagiaire->domaine ?? '') }}" placeholder="Votre domaine d'étude...">
                        @error('domaine') <span class="student-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="student-profile-docs">
                        <h3><i class="fa-regular fa-file-lines" aria-hidden="true"></i> Documents</h3>
                        <div class="mb-3">
                            <label for="cv" class="student-form-label">Curriculum Vitae (CV)</label>
                            @if(isset($stagiaire) && $stagiaire->cv_path)
                                <div class="mb-2">
                                    <a href="{{ route('stagiaire.cv') }}" class="student-table-action"><i class="fa-solid fa-download me-1" aria-hidden="true"></i> Télécharger le CV actuel</a>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('cv') is-invalid @enderror" id="cv" name="cv" accept=".pdf,.doc,.docx">
                            <div class="form-text">Formats acceptés : PDF, DOC, DOCX. Taille maximale : 5 Mo.</div>
                            @error('cv') <span class="student-form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="lettre_motivation" class="student-form-label">Lettre de motivation par défaut</label>
                            <textarea class="student-form-control @error('lettre_motivation') is-invalid @enderror" id="lettre_motivation" name="lettre_motivation" rows="4">{{ old('lettre_motivation', $stagiaire->lettre_motivation ?? '') }}</textarea>
                            @error('lettre_motivation') <span class="student-form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="student-profile-save">
                <button type="submit"><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>Enregistrer les modifications</button>
            </div>
        </form>
    </section>
    <section class="student-surface mt-3 p-4">
        <h2 class="h5">Connexion Google</h2>
        @if(auth()->user()->google_id)
            <p class="mb-0">Un compte Google est déjà lié à votre profil.</p>
        @else
            <p>Vous pouvez lier votre compte Google après vous être connecté à Stage Link.</p>
            <form method="POST" action="{{ route('google.link') }}">
                @csrf
                <button type="submit" class="student-table-action border-0">
                    <i class="fa-brands fa-google me-1" aria-hidden="true"></i> Lier mon compte Google
                </button>
            </form>
        @endif
    </section>
</div>
@endsection

@section('styles')
@include('stagiaire.partials.content-page-styles')
@endsection
