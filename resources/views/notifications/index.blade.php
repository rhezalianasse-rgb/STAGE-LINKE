@extends('layouts.app')
@section('title', 'Mes Notifications')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-gray-800">Notifications</h2>
                @if(isset($notifications) && $notifications->where('lu', false)->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-primary btn-sm">Tout marquer comme lu</button>
                    </form>
                @endif
            </div>

            <div class="card shadow-sm">
                <div class="list-group list-group-flush">
                    @forelse($notifications ?? [] as $notification)
                        <div class="list-group-item list-group-item-action p-3 {{ !$notification->lu ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 mt-1">
                                        <!-- Placeholder icon logic -->
                                        @if($notification->type == 'App\Notifications\NewCandidature')
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 text-primary">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        @elseif($notification->type == 'App\Notifications\CandidatureStatusUpdated')
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 text-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        @else
                                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 text-secondary">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-1 {{ !$notification->lu ? 'fw-bold' : '' }}">
                                            {{ $notification->message }}
                                        </p>
                                        <small class="text-muted"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if(!$notification->lu)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-link text-primary text-decoration-none" title="Marquer comme lu">
                                            <i class="fas fa-check"></i> Lu
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-5">
                            <div class="text-muted">
                                <i class="far fa-bell fa-3x mb-3"></i>
                                <h5>Aucune notification</h5>
                                <p>Vous êtes à jour dans vos notifications.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                @if(isset($notifications) && method_exists($notifications, 'links'))
                    <div class="card-footer bg-white pt-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
