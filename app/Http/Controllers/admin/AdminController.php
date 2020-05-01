<?php

namespace App\Http\Controllers\admin;


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
        $products = Product::all();
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

    public function updateProduct(Request $request)
    {
        // Form validation
        $request->validate([
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Get current user
        $product = Product::findOrFail('5');

        // Check if a product image has been uploaded
        if ($request->has('product_image')) {
            // Get image file
            $image = $request->file('product_image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($product->name . '_' . time());
            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user product image path in database to filePath
            $product->product_image = $filePath;
        }
        // Persist user record to database
        $product->save();

        // Return user back and show a flash message
        return view('admin.index', ['success' => 'Image uploaded']);
    }
}