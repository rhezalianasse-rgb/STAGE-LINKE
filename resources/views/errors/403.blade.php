@extends('layouts.app')

@section('title', 'Accès interdit')

@section('content')
<div class="container py-5 text-center">
    <p class="display-1 fw-bold text-primary">403</p>
    <h1 class="h3">Accès non autorisé</h1>
    <p class="text-muted">Vous n'avez pas les autorisations nécessaires pour consulter cette page.</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Retour à l'accueil</a>
</div>
@endsection
