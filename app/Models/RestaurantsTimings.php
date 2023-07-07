<?php

namespace App\Models;

use App\Models\Restaurants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantsTimings extends Model
{
    use HasFactory;

    public function restaurants()
    {
        return $this->belongsTo(Restaurants::class,'restaurant_id');
    }
    protected $fillable = [
        'name',
        'opening_time',
        'closing_time',
    ];
}
