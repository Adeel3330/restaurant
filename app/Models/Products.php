<?php

namespace App\Models;

use App\Models\Categories;
use App\Models\SubCategories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

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

    protected $fillable = [
        'name',
        'image',
        'category_id',
        'sub_category_id',
        'price',
        'description'

    ];
}
