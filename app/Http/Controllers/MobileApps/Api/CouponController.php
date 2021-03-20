<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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


    public function applyCoupon(Request $request){

        $user= auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $coupon=Coupon::with(['categories'=>function($categories){
            $categories->select('sub_category.id');
        }])
            ->where(DB::raw('BINARY code'), $request->coupon??null)
            ->first();

        if(!$coupon){
            return [
                'status'=>'failed',
                'message'=>'Invalid Coupon Applied',
            ];
        }

        if($coupon->isactive==false || !$coupon->getUserEligibility($user)){
            return [
                'status'=>'failed',
                'message'=>'Coupon Has Been Expired',
            ];
        }

        $items=Cart::with(['product.subcategory'])
            ->where('user_id', $user->id)
            ->get();

        if(!$items)
            return [
                'status'=>'failed',
                'message'=>'No items found in cart'
            ];

        $cost=0;
        $savings=0;
        $delivery_charge=25;
        //$itemdetails=[];
        foreach($items as $detail){
            $cost=$cost+$detail->product->price*$detail->quantity;
            $savings=$savings+($detail->product->cut_price-$detail->product->price)*$detail->quantity;
        }


        //$discount=$coupon->getCouponDiscount($cost)??0;
        $discount=Cart::getCouponDiscount($items, $coupon)??0;

        if($discount <= 0 || $discount > $cost)
        {
            return [
                'status'=>'failed',
                'message'=>'Coupon Cannot Be Applied',
            ];
        }

        $prices=[
            'price_total'=>$cost,
            'delivery_charge'=>$delivery_charge,
            'coupon_discount'=>$discount,
            'price_total_discount'=>$savings+$discount,
            'total_payble'=>$cost+$delivery_charge-$discount,
        ];


        return [

            'status'=>'success',
            'message'=>'Discount of Rs. '.$discount.' Applied Successfully',
            'prices'=>$prices,
        ];


    }
}
