<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facebook extends Model
{
    use HasFactory;

    protected $table = 'facebooks';

    protected $fillable = [
        'access_token',
        'user_id',
        'login_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
