<?php

namespace App\Services\Otp;

use LaravelEasyRepository\BaseService;

interface OtpService extends BaseService
{
    public function sendOtp($email);
    public function sendOtpNewEmail($email);
    public function verifyOtp($email, $otp);
    public function resetPassword($user, $dataPasswordUser);
}
