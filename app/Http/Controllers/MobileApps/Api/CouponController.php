<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\Cart;
use App\Models\Configuration;
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
        $delivery=Configuration::where('param', 'delivery_charge')->first();
        //$itemdetails=[];
        $delivery_charge=0;
        foreach($items as $detail){
                $cost=$cost+$detail->product->price*$detail->total_quantity;
                $savings=$savings+($detail->product->cut_price-$detail->product->price)*$detail->total_quantity;
                if($detail->type=='subscription'){
                    if($user->membership_expiry>=$detail->start_date){
                        $subscription_days=$detail->days->map(function($element){
                            return $element->id;
                        })->toArray();
                        $count_free_days=calculateDaysCountBetweenDate($detail->start_date, $user->membership_expiry, $subscription_days);
                        $delivery_charge=$delivery_charge+($detail->product->delivery_charge*$detail->total_quantity)-$detail->quantity*$detail->product->delivery_charge*$count_free_days;
                    }else{
                        $delivery_charge=$delivery_charge+($detail->product->delivery_charge*$detail->total_quantity);
                    }
                }else{
                    if(!isset($daywise_delivery_total[$detail->start_date]))
                        $daywise_delivery_total[$detail->start_date]=0;
                    $daywise_delivery_total[$detail->start_date]=$daywise_delivery_total[$detail->start_date]+$detail->product->price*$detail->quantity;
                }
        }

        if(!empty($daywise_delivery_total)){
            foreach($daywise_delivery_total as $key=>$val){
                if($user->membership_expiry < $key && $val< config('myconfig.delivery_charges_min_order')['non_member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }else if($user->membership_expiry >= $key && $val < config('myconfig.delivery_charges_min_order')['member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }
            }
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
            'price_total'=>round($cost,2),
            'delivery_charge'=>round($delivery_charge,2),
            'coupon_discount'=>round($discount,2),
            'price_total_discount'=>round($savings+$discount, 2),
            'total_payble'=>round($cost+$delivery_charge-$discount,2)
        ];


        return [

            'status'=>'success',
            'message'=>'Discount of Rs. '.$discount.' Applied Successfully',
            'prices'=>$prices,
        ];


    }
}
