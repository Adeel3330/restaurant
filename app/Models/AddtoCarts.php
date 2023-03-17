<?php

namespace App\Models;

use App\Models\Users;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddtoCarts extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'status'
    ];
}
