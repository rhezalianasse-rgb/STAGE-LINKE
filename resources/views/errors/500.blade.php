@extends('layouts.app')

@section('title', 'Erreur du serveur')

@section('content')
<div class="container py-5 text-center">
    <p class="display-1 fw-bold text-danger">500</p>
    <h1 class="h3">Une erreur est survenue</h1>
    <p class="text-muted">Le serveur n'a pas pu traiter votre demande. Réessayez plus tard.</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Retour à l'accueil</a>
</div>
@endsection
