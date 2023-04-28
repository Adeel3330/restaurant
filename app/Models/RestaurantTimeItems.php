<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTimeItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'restaurant_timings_id',
    ];

    public function restaurant_timings(){
       return $this->belongsTo(RestaurantsTimings::class);
    }
}
