<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    /**
     * The products that belong to the user.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }
}