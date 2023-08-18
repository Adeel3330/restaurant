<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    use HasFactory;

    public function restaurant()
    {
        // body...
        return $this->belongsTo(Restaurants::class);
    }
    public function category()
    {
        // body...
        return $this->belongsTo(Categories::class);
    }

    protected $fillable = [
        'image',
        'restaurant_id',

    ];
}
