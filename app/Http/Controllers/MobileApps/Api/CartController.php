<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\BookDay;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\SaveLaterProduct;
use App\Models\Size;
use App\Models\Slots;
use App\Models\TimeSlot;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartController extends Controller
{
    public function addcart(Request $request){

        $user=$request->user;

        $product=Product::active()->find($request->product_id);

        if(!$product)
            return [
                'status'=>'failed',
                'message'=>'Product is no longer available'
            ];

        //delete cart product
        if(!$request->quantity){
            return $this->deleteCartItem($request, $product, $user);
        }

        //return $user;
        $request->validate([
            'quantity'=>'required|integer|min:0',
            'product_id'=>'required|integer|min:1',
            'type'=>'required|string|in:once,subscription',
            'no_of_days'=>'required_if:type,subscription',
            'days'=>'required_if:type,subscription|array',
            'days.*'=>'nullable|integer|min:0|max:6',
        ]);

        if(!$product->can_be_subscribed  && $request->type =='subscription')
            return [
                'status'=>'failed',
                'message'=>'Product is not available for subscription'
            ];

        //check for out of stock
        if(!$product->stock)
            return [
                'status'=>'failed',
                'message'=>'Product is out of stock'
            ];

        //check for out of stock
//        if($request->quantity > $product->stock)
//            return [
//                'status'=>'failed',
//                'message'=>'Only '.$product->stock.' quantity available in stock'
//            ];

        if($request->quantity< $product->min_qty)
            //die;
            return [
                'status'=>'failed',
                'message'=>'Minimum buy quantity is '.$product->min_qty.'.'
            ];

        if($request->quantity> $product->max_qty)
            return [
                'status'=>'failed',
                'message'=>'Maximum buy quantity is '.$product->max_qty.'.'
            ];

        if($request->start_date && $request->time_slot){
            $date=$request->start_date;
            $ts=TimeSlot::findOrFail($request->time_slot);

            if(!$ts->checkTimings($date))
                return [
                    'status'=>'failed',
                    'message'=>'Please schedule different time slot'
                ];
            $ts=['id'=>$ts->id, 'date'=>$date];
        }else{
            $ts=TimeSlot::getNextDeliverySlot();
        }

        $cart=Cart::updateOrCreate(
            [
                'product_id'=>$request->product_id,
                'user_id'=>$user->id
                ],
            [
                'type'=>$request->type,
                'start_date'=>$ts['date'],
                'time_slot_id'=>$ts['id'],
                'no_of_days'=>($request->type=='subscription')?($request->no_of_days):1,
                'quantity'=>$request->quantity,
                'total_quantity'=>($request->type=='subscription')?($request->quantity*($request->no_of_days??15)):$request->quantity,
                ]);

        $product->cart_type=$request->type;
        $product->cart_quantity=$request->quantity;

        //adjust delivery days
        $cart->days()->sync([]);
        if($request->type=='subscription' && isset($request->days)){
            if(!empty($request->days)){
                $seldays=[];
                foreach($request->days as $d){
                    if(is_numeric($d)){
                        $seldays[]=$d;
                    }
                }
                $cart->days()->sync($seldays);

            }

        }

        $cart=Cart::getUserCart($user);
        $cart_total=$cart['total'];
        $price_total=$cart['price_total'];

        return [
            'status'=>'success',
            'message'=>'success',
            'product'=>$product,
            'cart_total'=>$cart_total,
            'price_total'=>round($price_total)
        ];

    }

    private function deleteCartItem(Request $request, $product, $user){
        $cart=Cart::where('product_id', $request->product_id)
            ->where('user_id', $user->id)->first();
        if($cart){
            $cart->days()->sync([]);
            //BookDay::where('cart_id',$cart->id)->delete();
            $cart->delete();
        }else{
            return [
                'status'=>'failed',
                'message'=>'Please select quantity'
            ];
        }

        $cart=Cart::getUserCart($user);
        $cart_total=$cart['total'];
        $price_total=$cart['price_total'];

        return [
            'status'=>'success',
            'message'=>'success',
            'product'=>$product,
            'cart_total'=>$cart_total,
            'price_total'=>round($price_total, 2)
        ];
    }

    public function getCartDetails(Request $request){
        $user=$request->user;

        if($user){
            //die;

            $items=Cart::with(['days', 'timeslot'])
                ->where('user_id', $user->id)
                ->get();

            if($items){
                $next_slot=TimeSlot::getNextDeliverySlot();
                Cart::updateManyItemTimeSlot($items, $next_slot);
            }

        }

        $deliveryaddress=CustomerAddress::with('area')
            ->where('delivery_active',1)
            ->where('user_id',$user->id)
            ->first();

        $cartitems=Cart::with(['product','days', 'timeslot'])
        ->where('user_id', $user->id)
        ->get();

        $delivery=Configuration::where('param', 'delivery_charge')->first();


        $walletdetails=Wallet::walletdetails($user->id);
        $balance = $walletdetails['balance'];

        $club_membersip=10;
        $delivery_charge=0;
        $total=0;
        $quantity=0;
        $price_total=0;
        $price_total_discount=0;
        $item_type_count=0;
        $cartitem=array();
        $cartitem['subscriptions']=[];
        $cartitem['once']=[];
        $out_of_stock=0;
        $eligible_goldcash=0;
        $daywise_delivery_total=[];
        foreach($cartitems as $c){
            if(!$c->product->isactive){
                $c->days()->sync([]);
                $c->delete();
                continue;
            }
            $item_type_count++;

            $total=$total+($c->product->price??0)*$c->total_quantity;
            $quantity=$quantity+$c->total_quantity;
            $price_total=$price_total+($c->product->price??0)*$c->total_quantity;
            $price_total_discount=$price_total_discount+(($c->product->cut_price??0)-($c->product->price??0))*$c->total_quantity;
            $eligible_goldcash=$eligible_goldcash+($c->product->price*$c->product->eligible_goldcash/100)*$c->total_quantity;

            if($c->type=='subscription'){

                $cartitem['subscriptions'][]=array(
                    'id'=>$c->id,
                    'name'=>$c->product->name??'',
                    'company'=>$c->product->company??'',
                    'image'=>$c->product->image,
                    'product_id'=>$c->product->id??'',
                    'unit'=>$c->product->unit??'',
                    'quantity'=>$c->quantity,
                    'type'=>$c->type,
                    'start_date'=>$c->start_date,
                    'time_slot'=>$c->time_slot_id,
                    'no_of_days'=>$c->no_of_days,
                    'price'=>$c->product->price,
                    'cut_price'=>$c->product->cut_price,
                    'days'=>$c->days,
                    'timeslot'=>$c->timeslot,
                    'stock'=>$c->product->stock,
                    'date_text'=>date('d M', strtotime($c->start_date)).' By'.' 7PM',
                );

                if($user->membership_expiry>=$c->start_date){
                    $subscription_days=$c->days->map(function($element){
                        return $element->id;
                    })->toArray();
                    $count_free_days=calculateDaysCountBetweenDate($c->start_date, $user->membership_expiry, $subscription_days);
                    $delivery_charge=$delivery_charge+($c->product->delivery_charge*$c->total_quantity)-$c->quantity*$c->product->delivery_charge*$count_free_days;
                }else{
                    $delivery_charge=$delivery_charge+($c->product->delivery_charge*$c->total_quantity);
                }


            }else{

                $cartitem['once'][]=array(
                    'id'=>$c->id,
                    'name'=>$c->product->name??'',
                    'company'=>$c->product->company??'',
                    'image'=>$c->product->image,
                    'product_id'=>$c->product->id??'',
                    'unit'=>$c->product->unit??'',
                    'quantity'=>$c->quantity,
                    'type'=>$c->type,
                    'start_date'=>$c->start_date,
                    'time_slot'=>$c->time_slot_id,
                    'no_of_days'=>$c->no_of_days,
                    'price'=>$c->product->price,
                    'cut_price'=>$c->product->cut_price,
                    'days'=>$c->days,
                    'timeslot'=>$c->timeslot,
                    'stock'=>$c->product->stock,
                    'date_text'=>date('d M', strtotime($c->start_date)).' By'.' 7PM',
                );


                if(!isset($daywise_delivery_total[$c->start_date]))
                    $daywise_delivery_total[$c->start_date]=0;
                $daywise_delivery_total[$c->start_date]=$daywise_delivery_total[$c->start_date]+$c->product->price*$c->quantity;
            }

            if(!$c->product->stock)
                $out_of_stock=1;
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

        $cashbackpoints=($eligible_goldcash < $walletdetails['cashback'])?$eligible_goldcash:$walletdetails['cashback'];

            return [
                'status'=>'success',
                'deliveryaddress'=>$deliveryaddress,
                'cartitem'=>$cartitem,
                'total'=>round($total),
                'price_total'=>round($price_total),
                'price_total_discount'=>round($price_total_discount),
                'balance'=>round($balance, 2),
                'cashbackpoints'=>round($cashbackpoints, 2),
                'club_membersip'=>round($club_membersip),
                'delivery_charge'=>round($delivery_charge),
                'quantity'=>$item_type_count,
                'coupon_discount'=>0,
                'payble_amount'=>round($price_total),
                //'coupons'=>$coupon,
                'out_of_stock'=>$out_of_stock,
            ];

        }

}
