<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('login')->withErrors([
                'email' => 'La connexion Google n’est pas encore configurée.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function link(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return back()->with('error', 'La connexion Google n’est pas encore configurée.');
        }

        $request->session()->put('oauth.google.linking_user_id', $request->user()->id);

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            $request->session()->forget('oauth.google.linking_user_id');

            return $this->oauthError($request, 'La connexion Google a été annulée.');
        }

        if (! $this->isConfigured()) {
            return $this->oauthError($request, 'La connexion Google n’est pas encore configurée.');
        }

        try {
            $identity = Socialite::driver('google')->user();
        } catch (InvalidStateException|IdentityProviderException|GuzzleException $exception) {
            Log::notice('Google OAuth callback failed.', ['exception' => $exception::class]);

            return $this->oauthError($request, 'La connexion Google a échoué. Veuillez réessayer.');
        }

        $email = Str::lower(trim((string) $identity->getEmail()));
        $googleId = (string) $identity->getId();
        $verifiedEmail = ($identity->user['verified_email'] ?? false) === true;

        if (! $verifiedEmail || $email === '' || $googleId === '' || Str::length($email) > 100) {
            $request->session()->forget('oauth.google.linking_user_id');

            return $this->oauthError($request, 'Google doit confirmer une adresse e-mail valide pour continuer.');
        }

        $linkingUserId = $request->session()->pull('oauth.google.linking_user_id');

        if ($linkingUserId !== null) {
            return $this->linkIdentity($request, $googleId, (int) $linkingUserId, $email);
        }

        $user = User::query()->where('google_id', $googleId)->first();

        if ($user) {
            if (Str::lower($user->email) !== $email || $user->statut !== 'actif') {
                return $this->oauthError($request, 'Impossible de confirmer ce compte Google. Connectez-vous avec votre adresse Stage Link.');
            }

            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('auth_session_version', $user->auth_session_version);

            return redirect()->intended($this->dashboardRoute($user));
        }

        if (User::query()->where('email', $email)->exists()) {
            return $this->oauthError($request, 'Ce compte existe déjà. Connectez-vous d’abord avec votre mot de passe, puis liez Google depuis votre profil.');
        }

        $nameParts = preg_split('/\s+/', trim((string) $identity->getName()), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $firstName = (string) ($identity->user['given_name'] ?? $nameParts[0] ?? 'Stagiaire');
        $lastName = (string) ($identity->user['family_name'] ?? implode(' ', array_slice($nameParts, 1)));

        $user = DB::transaction(function () use ($email, $googleId, $firstName, $lastName): User {
            $user = User::create([
                'nom' => Str::substr($lastName !== '' ? $lastName : 'Google', 0, 100),
                'prenom' => Str::substr($firstName !== '' ? $firstName : 'Stagiaire', 0, 100),
                'email' => $email,
                'mot_de_passe' => Hash::make(Str::random(64)),
                'role' => 'stagiaire',
                'statut' => 'actif',
                'email_verified_at' => now(),
                'google_id' => $googleId,
            ]);

            Stagiaire::create(['user_id' => $user->id]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('auth_session_version', $user->auth_session_version);

        return redirect()->intended($this->dashboardRoute($user));
    }

    private function linkIdentity(Request $request, string $googleId, int $userId, string $email): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->id !== $userId || ! $user->email_verified_at) {
            return redirect()->route('login')->withErrors([
                'email' => 'Reconnectez-vous avant de lier votre compte Google.',
            ]);
        }

        if (Str::lower(trim($user->email)) !== $email) {
            return redirect()->to($this->dashboardRoute($user))
                ->with('error', 'Le compte Google doit utiliser la même adresse e-mail que votre compte Stage Link.');
        }

        $existing = User::query()->where('google_id', $googleId)->first();

        if ($existing && $existing->id !== $user->id) {
            return back()->with('error', 'Ce compte Google est déjà associé à un autre compte Stage Link.');
        }

        if ($user->google_id && $user->google_id !== $googleId) {
            return back()->with('error', 'Un autre compte Google est déjà associé à ce profil.');
        }

        $user->forceFill(['google_id' => $googleId])->save();
        $request->session()->regenerate();

        return redirect()->to($this->dashboardRoute($user))->with('status', 'Votre compte Google a été lié.');
    }

    private function oauthError(Request $request, string $message): RedirectResponse
    {
        if ($request->user()) {
            return redirect()->to($this->dashboardRoute($request->user()))->with('error', $message);
        }

        return redirect()->route('login')->withErrors(['email' => $message]);
    }

    private function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function dashboardRoute(User $user): string
    {
        return match ($user->role) {
            'stagiaire' => route('stagiaire.dashboard'),
            'entreprise' => route('entreprise.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('home'),
        };
    }
}
