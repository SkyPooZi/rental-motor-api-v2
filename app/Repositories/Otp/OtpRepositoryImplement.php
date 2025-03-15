<?php

namespace App\Repositories\Otp;

use App\Models\Otp;
use Illuminate\Support\Carbon;
use LaravelEasyRepository\Implementations\Eloquent;

class OtpRepositoryImplement extends Eloquent implements OtpRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Otp $model;

    public function __construct(Otp $model)
    {
        $this->model = $model;
    }

    public function findByEmail($email)
    {
        return Otp::where('email', $email)
            ->where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function expiredOtps()
    {
        return Otp::where('expires_at', '<=', Carbon::now())
            ->delete();
    }

    public function findValidOtp($email, $otp)
    {
        return $this->model
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', Carbon::now())
            ->where('is_used', false)
            ->first();
    }

    public function updatePassword($user, $password)
    {
        $user->password = $password;
        $user->save();
        return $user;
    }
}
