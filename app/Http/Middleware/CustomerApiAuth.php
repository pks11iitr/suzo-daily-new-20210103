<?php

namespace App\Http\Middleware;

use Closure;

class CustomerApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return response()->json([
                'status'=>'failed',
                'message'=>'login'
            ], 200);

        $request->merge(compact('user'));
        return $next($request);
    }
}
