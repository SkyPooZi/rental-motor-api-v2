<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeLog extends Model
{
    use HasFactory;

    protected $table = 'change_logs';

    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'history_id',
        'previous_data',
        'updated_data',
        'changed_at',
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
