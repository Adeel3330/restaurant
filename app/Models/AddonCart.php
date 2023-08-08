<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonCart extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function addon(){
        return $this->belongsTo(Addon::class,'addon_id');
    }
    //  public function addons()
    // {
  
    //     return  $this->hasMany(AddonCart::class, 'cart_id')->with('addon')->withDefault([
    //         'name' => 'No Addon'
    //     ]);
    // }
}
