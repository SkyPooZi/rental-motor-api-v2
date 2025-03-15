<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'social_media_account',
        'address',
        'renter',
        'motorcycle_id',
        'start_date',
        'duration',
        'end_date',
        'renter_purpose',
        'motorcycle_receipt',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'point',
        'discount_id',
        'payment_method',
        'total_payment',
        'history_status',
        'review_id',
        'cancellation_date',
        'cancellation_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function motorcycleList()
    {
        return $this->belongsTo(MotorcycleList::class, 'motorcycle_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
