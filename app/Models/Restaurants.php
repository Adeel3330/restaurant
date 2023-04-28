<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'logitude',
        'latitude',
        'status',
        

    ];

    public function week_ids(){
       return  $this->hasMany(RestaurantTimeItems::class,'restaurant_id')->with('restaurant_timings');
    }


    
    
}
