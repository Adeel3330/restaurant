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
    public function addon()
    {
        return $this->belongsTo(Addon::class)->withDefault([
            'name' => 'No Addon'
        ]);
    }
    public function order()
    {
        return $this->hasMany(Orders::class,'id','order_id');
    }
    

    protected $fillable = [
        'product_id',
        'quantity',
        'payment',
        'order_id',
        'addon_id'
    ];
}
