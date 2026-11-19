<?php

namespace App\Enums;

enum OtpVerificationResult
{
    case Verified;
    case Invalid;
    case Expired;
    case Missing;
    case Locked;
}
