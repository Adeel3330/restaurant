<?php

namespace App\Models;

use App\Models\Users;
use App\Models\Products;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;
    protected $dates = ['created_at'];


    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class);
    }

    
    protected $fillable = [
        'user_id',
        'transaction_id',
        'restaurant_id',
        'address',
        'status',
 
    ];
}
