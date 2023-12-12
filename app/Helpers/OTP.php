<?php

namespace App\Helpers;

use App\Mail\SendOTP;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OTP
{
    private string $cachePrefix;

    private string $otp;

    private string $recipient;

    public function __construct($cachePrefix = 'otp_')
    {
        $this->cachePrefix = $cachePrefix;
    }

    public function generateForRecipient($recipient, $ttl = 6000): static
    {
        $this->recipient = $recipient;
        $this->otp = NumberHelper::getRandomNumber();

        $cacheKey = $this->cachePrefix.$recipient;
        Cache::put($cacheKey, $this->otp, $ttl);

        return $this;
    }

    public function sendViaEmail(): void
    {
        Mail::to($this->recipient)->queue((new SendOTP($this->otp))->onQueue('email_otp'));
    }

    public function verify($recipient, $otp): bool
    {
        $cacheKey = $this->cachePrefix.$recipient;

        return Cache::get($cacheKey) === $otp;
    }
}
