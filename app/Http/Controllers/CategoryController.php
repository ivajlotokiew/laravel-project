<?php

namespace App\Http\Controllers;

use App\Category;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
//        $this->middleware(['role:super-admin']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getProductsCategory($categoryId)
    {
        $params = [];
        $params['offset'] = 0;
        $params['limit'] = 8;
        $params['category_id'] = $categoryId;
        $products = $this->getProducts($params);

        if (!$products) {
            return response()->json(['Error' => 'Something goes wrong!'], 500);
        }

        return view('categories.category', compact('products'));
    }

    public function ajaxPostProductsCategory(Request $request)
    {
        $params = [];
        $params['offset'] = $request['offset'];
        $params['limit'] = $request['limit'];
        $params['category_id'] = $request['category_id'];
        $products = $this->getProducts($params);

        if (!$products) {
            return response()->json(['Error' => 'Something goes wrong!'], 500);
        }

        return response()->json($products);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxPostCategories()
    {
        $categories = Category::all();
        if (!$categories) {
            return response()->json(['Error' => 'Something goes wrong!'], 404);
        }

        return response()->json($categories);
    }

    public function ajaxPostUpdateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'category_image' => $request['category_image'] !== 'undefined' ? 'mimes:jpeg,bmp,png' : '',
        ]);

        $category = Category::find($request['id']);

        if ($category->name !== $request['name']) {
            $category->name = $request['name'];
        }

        if ($request->has('category_image') && $request['category_image'] !== 'undefined') {
            // Get image file
            $image = $request->file('category_image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($category['name'] . '_' . time());
            // Define folder path
            $folder = '/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user product image path in database to filePath
            $category->category_image = $filePath;
        }

        $category->save();

        return response()->json(['Success' => 'Category was successfully created!', 'category' => $category]);
    }

    private function getProducts($params)
    {
        try {
            $products = \DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id as id', 'products.name as name', 'products.price', 'products.description',
                    'products.created_at as created', 'products.product_image as img_url', 'categories.id as category_id',
                    'categories.name as category_name')
                ->where('categories.id', $params['category_id'])
                ->offset($params['offset'])
                ->limit($params['limit'])
                ->get();
            return $products;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
