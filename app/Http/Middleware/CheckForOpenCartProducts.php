<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForOpenCartProducts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $hasOpenCart = $user->carts()->where('confirmed', 0)->count();

        //If there isn't open cart then redirect
        if (!$hasOpenCart) {
            return redirect()->route('emptyCart');
        }

        //If there is open cart but missing products in it
        $openCart = $user->carts()->where('confirmed', 0)->first();
        $cProducts = $openCart->products()->get();
        if ($cProducts->count() === 0) {
            return redirect()->route('emptyCart');
        }

        return $next($request);
    }
}
