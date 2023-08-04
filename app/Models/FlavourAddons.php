<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlavourAddons extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function flavours()
    {
        return $this->belongsTo(ProductFlavours::class, 'flavour_id');
    }
}
