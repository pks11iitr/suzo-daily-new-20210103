<?php

namespace App\Http\Controllers\Customer\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function coupons(Request $request){

        $user=$request->user;
        $coupons=Coupon::active()->where('expiry_date', '>',date('Y-m-d'))
            ->select('id','code','description', 'expiry_date')
            ->get();

        return [
            'status'=>'success',
            "data"=>compact('coupons')
        ];

    }
}
