<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'criteria',
        'status',
        'favorite',
        'stock'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
