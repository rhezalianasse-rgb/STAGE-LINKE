<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthSessionIsCurrent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $request->hasSession() || ! $user) {
            return $next($request);
        }

        $sessionVersion = $request->session()->get('auth_session_version');

        if ($sessionVersion === null && Auth::guard()->viaRemember()) {
            $request->session()->put('auth_session_version', $user->auth_session_version);

            return $next($request);
        }

        if ($sessionVersion === null || (int) $sessionVersion !== $user->auth_session_version) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
