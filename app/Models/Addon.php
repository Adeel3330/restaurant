<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function category()
    {
        // body...
        return $this->belongsTo(Categories::class);
    }
    public function sub_category()
    {
        // body...
        return $this->belongsTo(SubCategories::class);
    }

    public function restaurant()
    {
        // body...
        return $this->belongsTo(Restaurants::class);
    }

    public function flavour_ids()
    {
        return  $this->hasMany(FlavourAddons::class, 'addon_id')->with('flavours');
    }

}
