<?php

namespace App\Http\Controllers\Product;

use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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
        $products = Product::all(['products.id as id', 'products.name as name', 'products.price', 'products.product_image as img_url',
            'products.category_id']);
        return view('products.index', compact('products'));
    }

    /**
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProduct($productId)
    {
        $product = \DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id as id', 'products.name as name', 'products.price', 'products.product_image as img_url',
                'categories.id as category_id', 'categories.name as category_name')
            ->where('products.id', $productId)
            ->get()->first();

        return view('products.product', compact('product'));
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
            $product = Product::find($request['id']);
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
                    'products.created_at as created', 'products.product_image as img_url', 'categories.id as category_id', 'categories.name as category_name')
                ->where('products.id', $request['id'])
                ->get();

            return response()->json($product);
        } catch (\Exception $ex) {
            return response()->json(['Error' => 'Something goes wrong!'], 500);
        }
    }

    public function ajaxAddProductToCart(Request $request) {
        $request->validate([
            'product_id' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        $product = Product::find($request['product_id']);

        //check if product exists
        if (!$product) {
            return response()->json(['Error' => 'There is no product with this id'], 400);
        }

        try {
            //create new Order Model
            $order = new Order();
            //Find current user
            $user = Auth::user();
            // Relate user with orders and create order field
            $user->orders()->save($order);
            //Create relation btw product and order
            $order->products()->attach($product->id,
                ["quantity" => $request['quantity'], "price" => $request['price']]);

            return response()->json(['Success' => 'Successfully registered order'], 200);
        } catch(\Exception $ex) {
            return response()->json(['Error' => 'Something goes wrong'], 500);
        }
    }

    private function getProducts($params)
    {
        try {
            $products = \DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id as id', 'products.name as name', 'products.price', 'products.product_image as img_url',
                    'categories.id as category_id', 'categories.name as category_name')
                ->offset($params['offset'])
                ->limit($params['length'])
                ->get();

            return $products;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
