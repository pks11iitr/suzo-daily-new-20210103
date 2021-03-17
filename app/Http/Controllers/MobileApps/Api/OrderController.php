<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\OrderConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\Wallet;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public  function index(){
        $user = auth()->guard('customerapi')->user();

    }

    public function initiateOrder(Request $request){
        $user=$request->user;

        $total_cost=0;
        $delivery_charge=0;
        $coupon_discount=0;
        $savings=0;
        $items=[];
        $days=[];

        if(!empty($request->coupon)){
            $coupon=Coupon::active()->where('code', $request->coupon)->first();
            if(!$coupon){
                return [
                    'status'=>'failed',
                    'message'=>'Invalid Coupon Applied'
                ];
            }
            if($coupon && !$coupon->getUserEligibility($user)){
                return [
                    'status'=>'failed',
                    'message'=>'Coupon Has Been Expired'
                ];
            }
        }


        $cart=Cart::with('days', 'timeslot', 'product')
            ->where('user_id', $user->id)
            ->get();

        if(!count($cart))
            return [
                'status'=>'failed',
                'message'=>'Cart is empty'
            ];

        $refid=env('MACHINE_ID').rand(1,9).rand(1,9).date('his').rand(1,9).rand(1,9);

        foreach($cart as $item) {
            if($item->type=='subscription'){
                $total_cost=$total_cost+$item->quantity*($item->product->price??0)*$item->no_of_days;
                $savings=$savings+$item->quantity*(($item->product->price??0)-($item->product->cut_price))*$item->no_of_days;
            }
            else{
                $total_cost=$total_cost+$item->quantity*$item->no_of_days;
                $savings=$savings+$item->quantity*($item->product->price??0)*$item->no_of_days;
            }

            $items[]=new OrderDetail($item->only('user_id', 'product_id', 'quantity','type','start_date','time_slot','no_of_days', 'total_quantity'));

            $days[$item->product_id]=$item->days->map(function($elem){
                return $elem->id;
            })->toArray();

        }

        //chnage it to dynamic later
        $delivery_charge=50;

        $order=Order::create([
            'user_id'=>$user->id,
            'refid'=>$refid,
            'status'=>'pending',
            'total_cost'=>$total_cost,
            'delivery_charge'=>$delivery_charge,
            'savings'=>$savings
        ]);

        $order->details()->saveMany($items);
        foreach($order->details as $d){
            if($d->type=='subscription')
                $d->days()->sync($days[$d->product_id]);
        }

        $order=Order::with('details.product.subcategories')->find($order->id);

        //find discount by applying coupon
        if($request->coupon){
            $coupon_discount=$order->getCouponDiscount($coupon);
            if($coupon_discount)
                $order->applyCoupon($coupon);
        }

        //$wallet=Wallet::walletdetails($order->user_id);

        //use wallet balance for remaining amount
        if($order->total_cost+$order->delivery_charge-$order->coupon_discount){
            if($request->use_balance==1) {
                $result=$this->useBalance($order);
            }
        }

        //use gold cash for remaining amount
        if(!$request->coupon){
            if($order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->balance_used){
                if($request->use_points==1) {
                    $result=$this->usePoints($order);
                }
            }
        }

        //save changes
        $order->save();

        $order_id=$order->id;

        return [
            'status'=>'success',
            'data'=>compact('order_id')
        ];

    }

    private function useBalance($order){
        $walletbalance=Wallet::balance($order->user_id);
        if($walletbalance<=0)
            return 0;

        $order->use_balance=true;
        if($walletbalance >= $order->total_cost+$order->delivery_charge-$order->coupon_discount) {
            $order->balance_used=$order->total_cost+$order->delivery_charge-$order->coupon_discount;
        }else {
            $order->balance_used=$walletbalance;
        }
        return $order->balance_used;
    }

    private function usePoints($order,$total_cost){

        $walletpoints=Wallet::points($order->user_id);
        if($walletpoints<=0)
            return 0;

        $eligible_cashback=Wallet::calculateEligibleCashback($total_cost, $walletpoints);

        $order->use_points=true;
        if($eligible_cashback >= $order->total_cost+$order->delivery_charge - $order->coupon_discount-$order->balance_used){
            $order->points_used=$order->total_cost+$order->delivery_charge-$order->coupon_discount;
        }else{
            $order->points_used=$eligible_cashback;
        }
        return $order->points_used;
    }
}
