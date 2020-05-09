<?php
/**
 * Created by PhpStorm.
 * User: ivaylo
 * Date: 7.5.2020 г.
 * Time: 0:47
 */

namespace App\Http\Controllers\Order;

use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
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

    public function index()
    {
        $orders = $this->getProductsOrders('all');
        return view('orders.index', compact('orders'));
    }

    public function getUnconfirmedProductsOrders()
    {

        return view('orders.order');
    }

    public function ajaxProductsOrdersQuantity()
    {
        $user = Auth::user();
        $orders = $user->orders()->get();
        $count = 0;

        if ($orders->count() === 0) return response(['count' => 0]);

        foreach ($orders as $order) {
            foreach ($order->products()->get() as $product) {
                $count += $product->pivot->quantity;
            }
        }

        return response(['count' => $count]);
    }

    private function getProductsOrders($orders)
    {
        $user = Auth::user();
        if ($orders === 'all') {
            $orders = $user->orders()->get();
        } else {
            $orders = $user->orders()->where('confirmed', 0)->get();
        }

        return $orders;

//        foreach ($orders as $order) {
//            foreach ($order->products()->get() as $product) {
//                $count += $product->pivot->quantity;
//            }
//        }
    }
}
