@extends('layouts.app')
@section('title', 'Tableau de Bord Admin')
@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de Bord</h1>
    </div>

    <!-- 1st Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-stat dashboard-stat-blue">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Total utilisateurs</p><strong class="dashboard-stat-value">{{ $stats['users'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-users"></i></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-stat dashboard-stat-green">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Stagiaires</p><strong class="dashboard-stat-value">{{ $stats['stagiaires'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-user-graduate"></i></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-stat dashboard-stat-teal">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Entreprises</p><strong class="dashboard-stat-value">{{ $stats['entreprises'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-building"></i></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-stat dashboard-stat-amber">
                <div class="card-body">
                    <div><p class="dashboard-stat-label">Offres actives</p><strong class="dashboard-stat-value">{{ $stats['offres'] ?? 0 }}</strong></div>
                    <span class="dashboard-stat-icon"><i class="fas fa-briefcase"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2nd Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Candidatures</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['candidatures_total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Candidatures En Attente</h6>
                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['candidatures_attente'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Candidatures Acceptées</h6>
                    <h3 class="mb-0 fw-bold text-success">{{ $stats['candidatures_acceptees'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Candidatures Refusées</h6>
                    <h3 class="mb-0 fw-bold text-danger">{{ $stats['candidatures_refusees'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Activité Récente</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Aucune activité récente à afficher pour le moment.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
