<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'image',
        'username',
        'full_name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'phone_number',
        'address',
        'role',
        'code',
        'point',
        'banned',
        'ban_reason',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->kode = static::generateRandomCode();
            $user->point = 0;
        });
    }

    public static function generateRandomCode()
    {
        return mt_rand(10000, 99999);
    }
}
