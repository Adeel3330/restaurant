<?php

namespace App\Models;

use App\Models\Users;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    protected $fillable = [
        'user_id',
        'transaction_id',
        'status'
    ];
}
