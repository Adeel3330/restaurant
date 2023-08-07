<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function restaurant()
    {
        return $this->belongsTo(Restaurants::class);
    }
}
