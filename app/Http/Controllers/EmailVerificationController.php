<?php

namespace App\Http\Controllers;

use App\Enums\OtpVerificationResult;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return redirect()->intended($this->dashboardRoute($user));
        }

        $otp = EmailVerificationOtp::query()->where('user_id', $user->id)->first();

        return view('auth.verify-email', [
            'email' => $user->email,
            'expiresAt' => $otp?->expires_at?->timestamp,
        ]);
    }

    public function verify(Request $request, EmailVerificationService $verification): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'regex:/\A[0-9]{6}\z/'],
        ]);

        $user = $request->user();
        $result = $verification->verifyCode($user, $validated['code']);

        return match ($result) {
            OtpVerificationResult::Verified => redirect()
                ->intended($this->dashboardRoute($user))
                ->with('status', 'Votre compte a été vérifié avec succès.'),
            OtpVerificationResult::Invalid => back()->withErrors(['code' => 'Code incorrect.']),
            OtpVerificationResult::Expired => back()->withErrors(['code' => 'Ce code a expiré. Demandez-en un nouveau.']),
            OtpVerificationResult::Missing => back()->withErrors(['code' => 'Aucun code actif. Demandez-en un nouveau.']),
            OtpVerificationResult::Locked => back()->withErrors(['code' => 'Trop de tentatives. Demandez un nouveau code.']),
        };
    }

    public function resend(Request $request, EmailVerificationService $verification): RedirectResponse
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return redirect()->intended($this->dashboardRoute($user));
        }

        $result = $verification->sendCode($user, resend: true);

        return back()->with(
            $result->succeeded() ? 'status' : 'error',
            $result->message()
        );
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
