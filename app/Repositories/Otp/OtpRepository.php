<?php

namespace App\Repositories\Otp;

use LaravelEasyRepository\Repository;

interface OtpRepository extends Repository
{
    public function findByEmail($email);
    public function expiredOtps();
    public function findValidOtp($email, $otp);
    public function updatePassword($user, $password);
}
