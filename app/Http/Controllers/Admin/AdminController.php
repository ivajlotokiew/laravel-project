<?php

namespace App\Http\Controllers\Admin;


use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    use UploadTrait;

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
        $products = \DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id as id', 'products.name as name', 'products.price', 'products.product_image as img_url',
                'categories.id as category_id', 'categories.name as category_name')
            ->get();

        $categories = Category::all();

        return view('admin.editProducts', compact('products', 'categories'));
    }

    public function ajaxPostCreateProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|min:5',
            'price' => 'required|numeric',
            'product_image' => $request['product_image'] !== 'undefined' ? 'mimes:jpeg,bmp,png' : '',
        ]);

        $product = new Product($validatedData);
        $category = Category::find($request['category_id']);

        if ($request->has('product_image') && $request['product_image'] !== 'undefined') {
            // Get image file
            $image = $request->file('product_image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($validatedData['name'] . '_' . time());
            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user product image path in database to filePath
            $product->product_image = $filePath;
        } else {
            $product->product_image = '/uploads/images/No image.png';
        }

        $category->products()->save($product);

        return response()->json(['Success' => 'Product was successfully created!']);
    }

    public function ajaxPostUpdateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|min:5',
            'price' => 'required|numeric',
            'product_image' => $request['product_image'] !== 'undefined' ? 'mimes:jpeg,bmp,png' : '',
        ]);

        $product = Product::find($request['id']);

        if ($product->name !== $request['name']) {
            $product->name = $request['name'];
        }

        if ($product->description !== $request['description']) {
            $product->description = $request['description'];
        }

        if ($product->price !== $request['price']) {
            $product->price = $request['price'];
        }

        if ($request->has('product_image') && $request['product_image'] !== 'undefined') {
            // Get image file
            $image = $request->file('product_image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($product['name'] . '_' . time());
            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user product image path in database to filePath
            $product->product_image = $filePath;
        }

        $category = Category::find($request['category_id']);
        $category->products()->save($product);

        return response()->json(['Success' => 'Product was successfully created!', 'product' => $product]);
    }

    public function ajaxGetEditedCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|min:1|Integer',
        ]);

        $categoryId = $request['category_id'];
        $category = Category::findOrFail($categoryId);

        return response()->json(['category' => $category]);
    }
}