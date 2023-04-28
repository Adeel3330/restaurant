<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlavourProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'flavour_id',
    ];
    public function flavours()
    {
        return $this->belongsTo(ProductFlavours::class,'flavour_id');
    }
}
