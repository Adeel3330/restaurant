<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategories extends Model
{
    use HasFactory;
    public function category()
    {
        // body...
        return $this->belongsTo(Categories::class);
    }

    protected $fillable = [
        'name',
        'image',
        'category_id'
    ];
}
