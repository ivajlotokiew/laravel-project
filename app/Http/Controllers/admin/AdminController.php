<?php

namespace App\Http\Controllers\admin;


use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;


class AdminController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    public function getCategories()
    {
        $categories = Category::all();

        return view('admin.editCategories', ['categories' => $categories]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProducts()
    {
        $products = Product::all();
        $categories = Category::all();

        return view('admin.editProducts', compact('products', 'categories'));
    }
}
