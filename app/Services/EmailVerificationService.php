<?php

namespace App\Services;

use App\Enums\OtpSendResult;
use App\Enums\OtpVerificationResult;
use App\Mail\EmailVerificationCode;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailVerificationService
{
    private const CODE_LIFETIME_MINUTES = 5;

    private const RESEND_COOLDOWN_SECONDS = 60;

    private const MAX_ATTEMPTS = 5;

    private const MAX_SENDS_PER_HOUR = 5;

    public function sendCode(User $user, bool $resend = false): OtpSendResult
    {
        if (config('mail.default') === 'log') {
            return OtpSendResult::MailNotConfigured;
        }

        $code = sprintf('%06d', random_int(0, 999999));
        $now = now();

        $result = DB::transaction(function () use ($user, $resend, $code, $now): OtpSendResult {
            $otp = EmailVerificationOtp::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($resend && $otp && $otp->last_sent_at->gt($now->copy()->subSeconds(self::RESEND_COOLDOWN_SECONDS))) {
                return OtpSendResult::Cooldown;
            }

            if ($resend && $otp) {
                $windowStartedAt = $otp->send_window_started_at;
                $sendCount = $otp->resend_count;

                if ($windowStartedAt->lte($now->copy()->subHour())) {
                    $windowStartedAt = $now;
                    $sendCount = 0;
                }

                if ($sendCount >= self::MAX_SENDS_PER_HOUR) {
                    return OtpSendResult::RateLimited;
                }

                $sendCount++;
            } else {
                $windowStartedAt = $now;
                $sendCount = 1;
            }

            EmailVerificationOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'code_hash' => Hash::make($code),
                    'expires_at' => $now->copy()->addMinutes(self::CODE_LIFETIME_MINUTES),
                    'attempts' => 0,
                    'resend_count' => $sendCount,
                    'send_window_started_at' => $windowStartedAt,
                    'last_sent_at' => $now,
                ]
            );

            return OtpSendResult::Sent;
        });

        if ($result !== OtpSendResult::Sent) {
            return $result;
        }

        try {
            Mail::to($user->email)->send(new EmailVerificationCode($code));
        } catch (TransportExceptionInterface $exception) {
            Log::warning('Email verification delivery failed.', [
                'user_id' => $user->id,
                'exception' => $exception::class,
            ]);

            return OtpSendResult::DeliveryFailed;
        }

        return OtpSendResult::Sent;
    }

    public function verifyCode(User $user, string $code): OtpVerificationResult
    {
        return DB::transaction(function () use ($user, $code): OtpVerificationResult {
            $otp = EmailVerificationOtp::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $otp) {
                return OtpVerificationResult::Missing;
            }

            if ($otp->expires_at->isPast()) {
                $otp->delete();

                return OtpVerificationResult::Expired;
            }

            if ($otp->attempts >= self::MAX_ATTEMPTS) {
                $otp->delete();

                return OtpVerificationResult::Locked;
            }

            if (! Hash::check($code, $otp->code_hash)) {
                $otp->attempts++;

                if ($otp->attempts >= self::MAX_ATTEMPTS) {
                    $otp->delete();

                    return OtpVerificationResult::Locked;
                }

                $otp->save();

                return OtpVerificationResult::Invalid;
            }

            $otp->delete();
            $user->forceFill(['email_verified_at' => now()])->save();

            return OtpVerificationResult::Verified;
        });
    }
}
