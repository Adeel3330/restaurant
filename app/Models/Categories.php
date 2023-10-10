<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    // public function restaurant()
    // {
    //     // body...
    //     return $this->belongsTo(Restaurants::class);
    // }
    protected $fillable = [
        'name',
        'image',
    ];
}
