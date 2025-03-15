<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Finance extends Model
{
    use HasFactory;

    protected $table = 'finances';

    protected $fillable = [
        'history_id',
        'total_motorcycle_price',
        'total_overtime_fee',
        'total_delivery_fee',
        'total_point_deduction',
        'total_discount_fee',
        'total_admin_fee',
        'total_reschedule_fee',
        'total_payment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function history()
    {
        return $this->belongsTo(History::class, 'history_id');
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
