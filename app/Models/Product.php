<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
