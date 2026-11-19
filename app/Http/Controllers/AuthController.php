<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->mot_de_passe,
            'statut' => 'actif',
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $request->session()->put('auth_session_version', $user->auth_session_version);
            if (! $user->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            if ($user->role === 'stagiaire') {
                return redirect()->intended('/stagiaire/dashboard');
            } elseif ($user->role === 'entreprise') {
                return redirect()->intended('/entreprise/dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $request->validate([
            'email' => 'required|email',
        ]);

        if (config('mail.default') === 'log') {
            return back()
                ->with('error', 'L’envoi des e-mails n’est pas configuré. Contactez l’administrateur.')
                ->withInput($request->only('email'));
        }

        Password::sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'Si cette adresse est associée à un compte, un lien de réinitialisation sera envoyé.'
        );
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'mot_de_passe' => ['required', 'string', PasswordRule::min(12)->mixedCase()->letters()->numbers()->symbols(), 'confirmed'],
        ]);

        $status = Password::reset(
            [
                'email' => $request->email,
                'password' => $request->mot_de_passe,
                'password_confirmation' => $request->mot_de_passe_confirmation,
                'token' => $request->token,
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'mot_de_passe' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'auth_session_version' => $user->auth_session_version + 1,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé.');
        }

        return back()->withErrors([
            'email' => __($status),
        ])->withInput($request->only('email'));
    }

    public function register(Request $request, EmailVerificationService $verification)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'mot_de_passe' => ['required', 'string', PasswordRule::min(12)->mixedCase()->letters()->numbers()->symbols(), 'confirmed'],
            'role' => 'sometimes|in:stagiaire',
            'telephone' => 'nullable|string|max:20',
        ]);

        $user = DB::transaction(function () use ($request): User {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'mot_de_passe' => Hash::make($request->mot_de_passe),
                'role' => 'stagiaire',
                'telephone' => $request->telephone,
                'statut' => 'actif',
                'email_verified_at' => null,
            ]);

            Stagiaire::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('auth_session_version', $user->auth_session_version);

        $sendResult = $verification->sendCode($user);

        return redirect()->route('verification.notice')->with(
            $sendResult->succeeded() ? 'status' : 'error',
            $sendResult->message()
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
