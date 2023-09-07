<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function order()
    {
        return $this->hasMany(Orders::class,'id','order_id');
    }
    public function addon()
    {
        return  $this->hasMany(AddonOrderItems::class, 'order_item_id')->with('addon');
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
        'product_id',
        'quantity',
        'payment',
        'order_id',
    ];
}
