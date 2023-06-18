<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverOrder extends Model
{
    use HasFactory;
    public $guarded = [];
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
