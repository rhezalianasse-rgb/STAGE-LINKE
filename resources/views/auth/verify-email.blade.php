@extends('layouts.app')

@section('body-class', 'auth-page')
@section('title', 'Vérification de l’adresse e-mail')

@section('content')
<section class="login-screen">
    <div class="login-story">
        <a href="{{ route('home') }}" class="login-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Stage Link">
        </a>
        <div class="login-story-copy">
            <p class="login-eyebrow"><span></span> VOTRE COMPTE · VOTRE AVENIR</p>
            <h1>Une dernière<br>étape pour<br><span>vous lancer.</span></h1>
            <p>Confirmez votre adresse e-mail pour accéder à votre espace Stage Link.</p>
        </div>
    </div>

    <div class="login-panel-wrap">
        <div class="login-panel otp-panel">
            <div class="login-heading">
                <p class="login-mobile-brand">STAGE LINK</p>
                <h2>Vérifiez votre e-mail</h2>
                <span class="login-heading-rule"></span>
                <p>Un code à 6 chiffres a été envoyé à <strong>{{ $email }}</strong>.</p>
            </div>

            @if(session('status'))
                <div class="otp-message is-success" role="status">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="otp-message is-error" role="alert">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="otp-message is-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}" class="otp-form" data-otp-form>
                @csrf
                <input type="hidden" name="code" data-otp-code>
                <div class="otp-digits" role="group" aria-label="Code de vérification à 6 chiffres">
                    @for($digit = 1; $digit <= 6; $digit++)
                        <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="{{ $digit === 1 ? 'one-time-code' : 'off' }}" aria-label="Chiffre {{ $digit }}" data-otp-digit>
                    @endfor
                </div>
                @error('code')
                    <div class="login-error">{{ $message }}</div>
                @enderror
                <p class="otp-countdown" data-otp-countdown data-expires-at="{{ $expiresAt ?? '' }}" aria-live="polite"></p>
                <button type="submit" class="login-submit">Vérifier mon adresse <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
            </form>

            <form method="POST" action="{{ route('verification.send') }}" class="otp-resend-form">
                @csrf
                <button type="submit" class="otp-resend">Renvoyer le code</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="otp-logout">
                @csrf
                <button type="submit">Se déconnecter</button>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
@include('auth.partials.styles')
<style>
    body.auth-page .otp-panel { max-width:540px; }
    body.auth-page .otp-heading strong { color:#fff; }
    body.auth-page .otp-message { margin:-8px 0 18px; padding:11px 13px; border-radius:9px; font-size:.82rem; }
    body.auth-page .otp-message.is-success { color:#d5ffeb; background:rgba(17,130,86,.32); }
    body.auth-page .otp-message.is-error { color:#ffe0e0; background:rgba(178,35,24,.3); }
    body.auth-page .otp-digits { display:flex; justify-content:center; gap:9px; margin:4px 0 12px; }
    body.auth-page .otp-digits input { width:52px; height:58px; border:1px solid rgba(220,228,255,.62); border-radius:11px; outline:0; color:#17295f; background:#fff; font:800 1.4rem 'DM Sans',sans-serif; text-align:center; }
    body.auth-page .otp-digits input:focus { border-color:#81a9ff; box-shadow:0 0 0 3px rgba(89,137,255,.24); }
    body.auth-page .otp-countdown { margin:0 0 18px; color:#d4defc; font-size:.78rem; text-align:center; }
    body.auth-page .otp-resend-form,body.auth-page .otp-logout { margin-top:12px; text-align:center; }
    body.auth-page .otp-resend,body.auth-page .otp-logout button { border:0; color:#a9c4ff; background:transparent; font:700 .82rem 'DM Sans',sans-serif; }
    body.auth-page .otp-resend:hover,body.auth-page .otp-logout button:hover { color:#fff; text-decoration:underline; }
    @media(max-width:420px) {
        body.auth-page .otp-digits { gap:6px; }
        body.auth-page .otp-digits input { width:42px; height:52px; }
    }
</style>
@endsection

@section('scripts')
<script>
    (() => {
        const form = document.querySelector('[data-otp-form]');
        if (!form) return;

        const digits = Array.from(form.querySelectorAll('[data-otp-digit]'));
        const code = form.querySelector('[data-otp-code]');
        const syncCode = () => {
            code.value = digits.map((input) => input.value).join('');
        };

        digits.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(-1);
                if (input.value && digits[index + 1]) digits[index + 1].focus();
                syncCode();
            });
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && digits[index - 1]) digits[index - 1].focus();
                if (event.key === 'ArrowLeft' && digits[index - 1]) digits[index - 1].focus();
                if (event.key === 'ArrowRight' && digits[index + 1]) digits[index + 1].focus();
            });
        });

        form.addEventListener('paste', (event) => {
            const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            if (!pasted) return;
            event.preventDefault();
            digits.forEach((input, index) => { input.value = pasted[index] || ''; });
            digits[Math.min(pasted.length, digits.length) - 1].focus();
            syncCode();
        });

        form.addEventListener('submit', (event) => {
            syncCode();
            if (code.value.length !== 6) {
                event.preventDefault();
                digits.find((input) => !input.value)?.focus();
            }
        });

        const countdown = document.querySelector('[data-otp-countdown]');
        const expiresAt = Number(countdown?.dataset.expiresAt);
        const updateCountdown = () => {
            if (!expiresAt) {
                countdown.textContent = 'Vous pouvez demander un code si vous ne l’avez pas reçu.';
                return;
            }
            const secondsLeft = Math.max(0, Math.ceil(expiresAt - Date.now() / 1000));
            countdown.textContent = secondsLeft
                ? `Ce code expire dans ${Math.floor(secondsLeft / 60)}:${String(secondsLeft % 60).padStart(2, '0')}.`
                : 'Ce code a expiré. Demandez-en un nouveau.';
        };
        updateCountdown();
        if (expiresAt) window.setInterval(updateCountdown, 1000);
    })();
</script>
@endsection
