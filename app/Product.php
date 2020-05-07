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

    /**
     * The orders that belong to the user.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function getImageAttribute()
    {
        return $this->product_image;
    }


}
