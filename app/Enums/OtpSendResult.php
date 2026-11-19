<?php

namespace App\Enums;

enum OtpSendResult
{
    case Sent;
    case Cooldown;
    case RateLimited;
    case MailNotConfigured;
    case DeliveryFailed;

    public function message(): string
    {
        return match ($this) {
            self::Sent => 'Un code de vérification a été envoyé à votre adresse e-mail.',
            self::Cooldown => 'Veuillez patienter avant de demander un autre code.',
            self::RateLimited => 'La limite de renvois est atteinte. Réessayez dans une heure.',
            self::MailNotConfigured => 'L’envoi des e-mails n’est pas configuré. Contactez l’administrateur.',
            self::DeliveryFailed => 'L’e-mail n’a pas pu être envoyé. Réessayez plus tard.',
        };
    }

    public function succeeded(): bool
    {
        return $this === self::Sent;
    }
}
