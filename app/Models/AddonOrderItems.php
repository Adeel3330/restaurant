<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonOrderItems extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }
}
