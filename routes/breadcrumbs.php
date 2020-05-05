<?php

// Categories
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('categories', function ($trail) {
    $trail->parent('home');
    $trail->push('Categories', route('categories'));
});

Breadcrumbs::for('category', function ($trail, $products) {
    $trail->parent('categories');
    $trail->push($products->category_name, route('category', $products->category_id));
});


Breadcrumbs::for('products', function ($trail) {
    $trail->parent('home');
    $trail->push('Products', route('products'));
});

Breadcrumbs::for('product', function ($trail, $product) {
    $trail->parent('products');
    $trail->push($product->name, route('product', $product->id));
});
