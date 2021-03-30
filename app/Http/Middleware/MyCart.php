<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;

class MyCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->guard('customerapi')->user();
        $cart=Cart::getUserCart($user);
        $request->merge([
            'cart'=>$cart['cart']??[],
            'cart_count'=>$cart['total']??0,
            'cart_total'=>$cart['price_total']??0,
            'user'=>$user
        ]);
        return $next($request);
    }
}
