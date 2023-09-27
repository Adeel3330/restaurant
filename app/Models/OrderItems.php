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

    

    protected $fillable = [
        'product_id',
        'quantity',
        'payment',
        'order_id',
    ];
}
