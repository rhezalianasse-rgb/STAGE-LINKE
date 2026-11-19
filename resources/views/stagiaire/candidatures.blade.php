@extends('layouts.app')

@section('body-class', 'student-content-page student-applications-page')
@section('title', 'Mes Candidatures - Stagiaire')

@section('content')
@include('stagiaire.partials.page-hero', [
    'breadcrumb' => 'Mes Candidatures',
    'prefix' => 'Mes',
    'accent' => 'Candidatures',
    'description' => 'Suivez l’état de vos candidatures et restez informé des réponses des entreprises.',
    'icon' => 'fa-clipboard-check',
])

<div class="student-page-content">
    <section class="student-surface student-applications-card">
        <div class="student-data-table-wrap">
            <table class="student-data-table">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-briefcase me-2 text-primary" aria-hidden="true"></i>Offre</th>
                        <th><i class="fa-solid fa-building me-2 text-primary" aria-hidden="true"></i>Entreprise</th>
                        <th><i class="fa-regular fa-calendar-days me-2 text-primary" aria-hidden="true"></i>Date de candidature</th>
                        <th><i class="fa-solid fa-chart-simple me-2 text-primary" aria-hidden="true"></i>Statut</th>
                        <th><i class="fa-solid fa-bolt me-2 text-warning" aria-hidden="true"></i>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures ?? [] as $candidature)
                        <tr>
                            <td class="fw-bold">{{ $candidature->offre->titre ?? 'Offre inconnue' }}</td>
                            <td>{{ $candidature->offre->entreprise->nom_entreprise ?? 'Entreprise' }}</td>
                            <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($candidature->statut == 'acceptee')
                                    <span class="student-status accepted"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Acceptée</span>
                                @elseif($candidature->statut == 'refusee')
                                    <span class="student-status refused"><i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> Refusée</span>
                                @else
                                    <span class="student-status pending"><i class="fa-regular fa-clock" aria-hidden="true"></i> En attente</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($candidature->offre_id))
                                    <a href="{{ route('offres.show', $candidature->offre_id) }}" class="student-table-action">Voir l'offre <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="student-empty-state">
                                    <span class="student-empty-art"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>
                                    <h3>Vous n'avez pas encore postulé à une offre.</h3>
                                    <p>Explorez les offres disponibles et trouvez votre prochaine opportunité !</p>
                                    <a href="{{ route('offres.index') }}" class="student-empty-cta"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Parcourir les offres</a>
                                </div>
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
    </section>
</div>
@endsection

@section('styles')
@include('stagiaire.partials.content-page-styles')
@endsection
