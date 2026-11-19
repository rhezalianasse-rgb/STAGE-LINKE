@extends('layouts.app')

@section('title', 'Page introuvable')

@section('content')
<div class="container py-5 text-center">
    <p class="display-1 fw-bold text-primary">404</p>
    <h1 class="h3">Page introuvable</h1>
    <p class="text-muted">La page demandée n'existe pas ou n'est plus disponible.</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Retour à l'accueil</a>
</div>
@endsection
