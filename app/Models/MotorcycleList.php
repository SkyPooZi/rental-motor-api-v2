<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MotorcycleList extends Model
{
    use HasFactory;

    protected $table = 'motorcycle_lists';

    protected $fillable = [
        'image',
        'name',
        'type',
        'brand',
        'stock',
        'price_per_day',
        'price_per_week',
        'delivery_price',
        'status',
        'unavailable_start_date',
        'unavailable_end_date',
        'is_hidden',
    ];
}
