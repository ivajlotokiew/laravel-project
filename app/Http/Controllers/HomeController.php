<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $offset = 0;
        $limit = 10;

        $products = \DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id as id', 'products.name as name', 'categories.id as category_id',
                'categories.name as category_name')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $categories = Category::all();
        $data = [];
        $data['products'] = $products;
        $data['categories'] = $categories;

        return view('home', compact('products', 'categories'));
    }


}
