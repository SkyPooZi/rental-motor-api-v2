<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'image',
        'discount_code',
        'discount_name',
        'discount_price',
        'start_date',
        'end_date',
        'is_hidden',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($diskon) {
            $diskon->kode_diskon = self::generateRandomCode();
        });
    }

    public static function generateRandomCode()
    {
        $letters = '';
        for ($i = 0; $i < 5; $i++) {
            $letters .= chr(rand(65, 90));
        }

        $numbers = rand(100, 999);

        return $letters . $numbers;
    }
}
