<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'product_image', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageAttribute()
    {
        return $this->product_image;
    }
}
