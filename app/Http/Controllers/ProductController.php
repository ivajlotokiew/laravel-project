<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Category;

class ProductController extends Controller
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
        $params = [];
        $params['offset'] = 0;
        $params['length'] = 8;

        $products = $this->getProducts($params);
        $categories = $this->getCategories();

        return view('home', compact('products', 'categories'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxPostGetProducts(Request $request)
    {
        $params = [];
        $params['offset'] = $request['offset'];
        $params['length'] = $request['length'];
        $data = $this->getProducts($params);

        if (!$data) {
            return response()->json(['Error' => 'Somtething goes wrong!'], 404);
        }

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxPostDeleteProduct(Request $request)
    {
        if (!isset($request['id'])) {
            return response()->json(['Error' => 'There is no product id provided!'], 404);
        }

        try {
            $product = Product::find($request[888]);
            $product->delete();

            return response()->json(['Success' => 'Product was successfully deleted!']);
        } catch (\Exception $ex) {
            return response()->json(['Error' => 'Something goes wrong!'], 500);
        }
    }

    public function ajaxPostGetProduct(Request $request)
    {
        if (!isset($request['id'])) {
            return response()->json(['Error' => 'There is no product id provided!'], 404);
        }

        try {
            $product = \DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id as id', 'products.name as name', 'products.price', 'products.description',
                    'products.created_at as created', 'categories.id as category_id', 'categories.name as category_name')
                ->get()->first();

            return response()->json($product);
        } catch (\Exception $ex) {
            return response()->json(['Error' => 'Something goes wrong!'], 500);
        }
    }

    private function getProducts($params)
    {
        try {
            $products = \DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id as id', 'products.name as name', 'products.price', 'categories.id as category_id',
                    'categories.name as category_name')
                ->offset($params['offset'])
                ->limit($params['length'])
                ->get();

            return $products;
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            return false;
        }
    }

    /**
     * @return array
     */
    private function getCategories()
    {
        $categories = Category::all();
        return $categories;
    }
}
