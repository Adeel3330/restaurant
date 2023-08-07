<?php

namespace App\Models;

use App\Models\AddonCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addon extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function category()
    {
        // body...
        return $this->belongsTo(AddonCategory::class,'category_id');
    }
    public function sub_category()
    {
        // body...
        return $this->belongsTo(AddonSubCategory::class,'sub_category_id');
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
