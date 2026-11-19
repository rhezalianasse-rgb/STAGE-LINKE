<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\StagiaireController;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Stagiaire;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Stage Link Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome', [
        'homeStats' => [
            'offres' => Offre::publiclyAvailable()->count(),
            'entreprises' => Entreprise::count(),
            'stagiaires' => Stagiaire::count(),
        ],
    ]);
})->name('home');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('throttle:10,1')->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->middleware('throttle:10,1')->name('google.callback');
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:6,1')
    ->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:6,1')
    ->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'active', 'auth.session', 'auth.version'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('verification.verify');
    Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('verification.send');
});

Route::post('/auth/google/link', [GoogleAuthController::class, 'link'])
    ->middleware(['auth', 'active', 'auth.session', 'auth.version', 'verified.email', 'throttle:10,1'])
    ->name('google.link');

// Routes publiques des offres
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
Route::get('/offres/{id}', [OffreController::class, 'show'])->name('offres.show');
Route::get('/profile-photos/{filename}', [StagiaireController::class, 'profilePhoto'])
    ->where('filename', '[A-Za-z0-9][A-Za-z0-9._-]*\\.[A-Za-z0-9]+')
    ->name('profile-photos.show');

// Routes authentifiées
Route::middleware(['auth', 'active', 'auth.session', 'auth.version', 'verified.email'])->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'stagiaire' => redirect()->route('stagiaire.dashboard'),
            'entreprise' => redirect()->route('entreprise.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    Route::get('/profil', function () {
        return match (auth()->user()->role) {
            'stagiaire' => redirect()->route('stagiaire.profil'),
            'entreprise' => redirect()->route('entreprise.profil'),
            default => abort(404),
        };
    })->name('profile');

    Route::get('/mes-candidatures', fn () => redirect()->route('stagiaire.candidatures'))
        ->middleware('role:stagiaire');
    Route::get('/mes-favoris', fn () => redirect()->route('stagiaire.favoris'))
        ->middleware('role:stagiaire');
    Route::get('/mes-offres', fn () => redirect()->route('entreprise.offres.index'))
        ->middleware('role:entreprise');
    Route::get('/candidatures-recues', fn () => redirect()->route('entreprise.candidatures'))
        ->middleware('role:entreprise');

    // Notifications (toutes les roles)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
});

// Routes Stagiaire
Route::middleware(['auth', 'active', 'auth.session', 'auth.version', 'verified.email', 'role:stagiaire'])->prefix('stagiaire')->name('stagiaire.')->group(function () {
    Route::get('/dashboard', [StagiaireController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [StagiaireController::class, 'profil'])->name('profil');
    Route::get('/cv', [StagiaireController::class, 'downloadCv'])->name('cv');
    Route::put('/profil', [StagiaireController::class, 'updateProfil'])->name('profil.update');
    Route::get('/competences', [StagiaireController::class, 'competences'])->name('competences');
    Route::post('/competences', [StagiaireController::class, 'addCompetence'])->name('competences.add');
    Route::delete('/competences/{id}', [StagiaireController::class, 'removeCompetence'])->name('competences.remove');
    Route::get('/candidatures', [StagiaireController::class, 'mesCandidatures'])->name('candidatures');
    Route::get('/favoris', [StagiaireController::class, 'mesFavoris'])->name('favoris');
    Route::post('/offres/{id}/postuler', [OffreController::class, 'postuler'])->name('offres.postuler');
    Route::post('/offres/{id}/favori', [OffreController::class, 'toggleFavori'])->name('offres.favori');
});

// Routes Entreprise
Route::middleware(['auth', 'active', 'auth.session', 'auth.version', 'verified.email', 'role:entreprise'])->prefix('entreprise')->name('entreprise.')->group(function () {
    Route::get('/dashboard', [EntrepriseController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [EntrepriseController::class, 'profil'])->name('profil');
    Route::put('/profil', [EntrepriseController::class, 'updateProfil'])->name('profil.update');
    Route::get('/offres', [OffreController::class, 'mesOffres'])->name('offres.index');
    Route::get('/offres/create', [OffreController::class, 'create'])->name('offres.create');
    Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
    Route::get('/offres/{id}/edit', [OffreController::class, 'edit'])->name('offres.edit');
    Route::put('/offres/{id}', [OffreController::class, 'update'])->name('offres.update');
    Route::delete('/offres/{id}', [OffreController::class, 'destroy'])->name('offres.destroy');
    Route::get('/candidatures', [EntrepriseController::class, 'mesCandidatures'])->name('candidatures');
    Route::get('/candidatures/{id}', [EntrepriseController::class, 'voirCandidature'])->name('candidatures.show');
    Route::get('/candidatures/{id}/cv', [EntrepriseController::class, 'downloadCv'])->name('candidatures.cv');
    Route::put('/candidatures/{id}', [EntrepriseController::class, 'updateCandidature'])->name('candidatures.update');
});

// Routes Admin
Route::middleware(['auth', 'active', 'auth.session', 'auth.version', 'verified.email', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des stagiaires
    Route::get('/stagiaires', [AdminController::class, 'stagiaires'])->name('stagiaires');
    Route::get('/stagiaires/{id}', [AdminController::class, 'showStagiaire'])->name('stagiaires.show');
    Route::get('/stagiaires/{id}/cv', [AdminController::class, 'downloadStagiaireCv'])->name('stagiaires.cv');
    Route::delete('/stagiaires/{id}', [AdminController::class, 'deleteStagiaire'])->name('stagiaires.delete');

    // Gestion des entreprises
    Route::get('/entreprises', [AdminController::class, 'entreprises'])->name('entreprises');
    Route::get('/entreprises/create', [AdminController::class, 'createEntreprise'])->name('entreprises.create');
    Route::post('/entreprises', [AdminController::class, 'storeEntreprise'])->name('entreprises.store');
    Route::get('/entreprises/{id}', [AdminController::class, 'showEntreprise'])->name('entreprises.show');
    Route::delete('/entreprises/{id}', [AdminController::class, 'deleteEntreprise'])->name('entreprises.delete');

    // Gestion des offres
    Route::get('/offres', [AdminController::class, 'offres'])->name('offres');
    Route::delete('/offres/{id}', [AdminController::class, 'deleteOffre'])->name('offres.delete');

    // Gestion des candidatures
    Route::get('/candidatures', [AdminController::class, 'candidatures'])->name('candidatures');
    Route::delete('/candidatures/{id}', [AdminController::class, 'deleteCandidature'])->name('candidatures.delete');

    // Gestion des spécialités
    Route::get('/specialites', [AdminController::class, 'specialites'])->name('specialites');
    Route::post('/specialites', [AdminController::class, 'storeSpecialite'])->name('specialites.store');
    Route::put('/specialites/{id}', [AdminController::class, 'updateSpecialite'])->name('specialites.update');
    Route::delete('/specialites/{id}', [AdminController::class, 'deleteSpecialite'])->name('specialites.delete');

    // Gestion des compétences
    Route::get('/competences', [AdminController::class, 'competences'])->name('competences');
    Route::post('/competences', [AdminController::class, 'storeCompetence'])->name('competences.store');
    Route::put('/competences/{id}', [AdminController::class, 'updateCompetence'])->name('competences.update');
    Route::delete('/competences/{id}', [AdminController::class, 'deleteCompetence'])->name('competences.delete');

    // Toggle statut utilisateur
    Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
});
