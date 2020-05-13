<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class Cart extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The products that belong to the order.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }

    static function totalPrice(Cart $cart)
    {
        $products = $cart->products()->get();
        $price = 0;
        foreach ($products as $product) {
            $price += $cart->products()->where('product_id', $product->id)->first()->pivot->price;
        }

        return $price;
    }
    
}
