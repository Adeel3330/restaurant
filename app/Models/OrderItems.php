<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

        return  $this->hasMany(AddonOrderItems::class, 'order_item_id')->with('addon')->withDefault([
            'name' => 'No Addon'
        ]);
    }

    protected $fillable = [
        'product_id',
        'quantity',
        'payment',
        'order_id',
    ];
}
