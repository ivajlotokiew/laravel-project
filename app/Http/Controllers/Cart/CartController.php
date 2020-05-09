<?php
/**
 * Created by PhpStorm.
 * User: ivaylo
 * Date: 7.5.2020 г.
 * Time: 0:47
 */

namespace App\Http\Controllers\Cart;

use App\Cart;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
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

    public function ajaxProductsCartQuantity()
    {
        $user = Auth::user();
        $carts = $user->carts()->get();

        if (empty($carts)) return response(['quantity' => 0]);

        $count = $carts->where('confirmed', 0)->count();
        if ($count === 0) return response(['quantity' => 0]);

        if ($count === 1) {
            $cartProducts = $carts->where('confirmed', 0)->first()->products()->get();
            if ($cartProducts->count() > 0) {
                $quantity = 0;
                foreach ($cartProducts as $product) {
                    $quantity += $product->pivot->quantity;
                }

                return response(['quantity' => $quantity]);
            } else {
                return response(['quantity' => 0]);
            }
        } elseif ($count > 1) {
            return response(['error' => 'There are more then one open carts'], 400);
        } else {
            return response(['error' => 'Something goes wrong'], 500);
        }
    }

    public function ajaxAddProductToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $carts = $user->carts()->get();
            $product = Product::find($request['product_id']);
            if (empty($carts) || $carts->where('confirmed', 0)->count() === 0) {
                $openCart = new Cart();
                $openCart->created_at = date('Y-m-d');
                $user->carts()->save($openCart);
                $openCart->products()->sync([$product->id => ['quantity' => $request['quantity'],
                    'price' => $request['price']]]);
                DB::commit();
                return response(['Success' => 'Successfully added product to open cart!']);
            }

            if ($carts->where('confirmed', 0)->count() === 1) {
                $openCart = $carts->where('confirmed', 0)->first();
                $products = $openCart->products()->get();
                if ($products->count() > 0) {
                    $productAlreadyInCart = false;
                    foreach ($products as $prd) {
                        if ($prd->id === $product->id) {
                            $openCart->products()->updateExistingPivot(
                                $prd->id, ['quantity' => $openCart->products()->where('product_id', $prd->id)->first()->pivot->quantity + $request['quantity'],
                                'price' => $request['price']]);
                            $productAlreadyInCart = true;
                            break;
                        }
                    }

                    if (!$productAlreadyInCart) {
                        $openCart->products()->attach(array($product->id => array('quantity' => $request['quantity'], 'price' => $request['price'])));
                    }
                }

                DB::commit();
                return response(['Success' => 'Successfully added product to open cart!']);
            } else {
                DB::rollBack();
                return response(['Error' => 'Something goes wrong'], 400);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['Error' => 'Server error'], 500);
        }
    }

    public function ajaxGetProductsQuantityToCart() {
        $user = Auth::user();
        $hasOpenCart = $user->carts()->where('confirmed', 0)->count();
        if (!$hasOpenCart) {
            return response(['Error' => 'There is no open cart'], 400);
        } else {
            $openCart = $user->carts()->where('confirmed', 0)->first();
            $products = $openCart->products()->get();
            $quantity = 0;
            foreach ($products as $product) {
                $quantity += $openCart->products()->where('product_id', $product->id)->first()->pivot->quantity;
            }

            return response(['quantity' => $quantity]);
        }
    }
}
