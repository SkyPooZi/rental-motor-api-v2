<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentNotification extends Model
{
    use HasFactory;

    protected $table = 'payment_notifications';

    protected $fillable = [
        'user_id',
        'history_id',
        'message',
        'total_amount',
        'due_date',
        'is_hidden',
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

    public function changeLog()
    {
        return $this->belongsTo(ChangeLog::class, 'change_log_id');
    }
}
