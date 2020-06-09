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
use App\Mail\OrderShipped;;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


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

    public function ajaxAddCartProductsToOrder(Request $request)
    {
        $request->validate([
            'cart_id' => ['required', 'integer']
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $carts = $user->carts()->get();
            $openCart = $carts->where('confirmed', 0)->first();
            if ($openCart->where('confirmed', 0)->count() === 0) {
                return response(['Error' => 'Cart is already confirmed!'], 400);
            }

            if ($openCart->products()->get()->count() === 0) {
                return response(['Error' => 'Cart is empty!'], 400);
            }

            $products = $openCart->products()->get();

            $order = new Order();
            $user->orders()->save($order);
            foreach ($products as $product) {
                $order->products()->attach(array($product->id => array(
                    'quantity' => $product->pivot->quantity, 'price' => $product->price * $product->pivot->quantity)));
            }

            $openCart->update(['confirmed' => 1]);
//            Mail::to($user)->send(new OrderShipped($order));
            DB::commit();
            return response(['Success' => 'Successfully added cart products to Order']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['Error' => $ex->getMessage()], 500);
        }
    }

    public function index()
    {
        $orders = $this->getProductsOrders('all');
        return view('orders.index', compact('orders'));
    }

    public function orderConfirmed() {
        return view('orders.confirmed');
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
