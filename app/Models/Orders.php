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

    public function getCreatedAtAttribute($value)
    {
        // Convert the 'created_at' timestamp to a Carbon instance
        $createdAt = Carbon::parse($value);

        // Add 2 hours to the 'created_at' timestamp
        $createdAt->addHours(2);

        // Return the modified timestamp
        return $createdAt;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->getCreatedAtAttribute($value),
            set: fn (string $value) => $value,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->getCreatedAtAttribute($value),
            set: fn (string $value) => $value,
        );
    }
    
    protected $fillable = [
        'user_id',
        'transaction_id',
        'restaurant_id',
        'address',
        'status',
 
    ];
}
