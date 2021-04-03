<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\ItemCancelled;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\TimeSlot;
use App\Models\Wallet;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public  function index(){
        $user = auth()->guard('customerapi')->user();

        $ordersobj=Order::with('details.product')
                    ->where('user_id', $user->id)
                    ->where('status', '!=', 'pending')
                    ->orderBy('id', 'desc')
                    ->get();
        $orders=[];
        foreach($ordersobj as $order){

            $orders[]=[
                'id'=>$order->id,
                'image'=>$order->details[0]->product->image??'',
                'price'=>$order->total_cost,
                'refid'=>$order->refid,
                'order_text'=>($order->details[0]->product->name??'').' '.(count($order->details)>1?'+'.(count($order->details)-1).' more items':''),
                 'date_time'=>date('D, d M,Y')
            ];
        }

        return [
            'status'=>'success',
            'data'=>compact('orders')
        ];

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

        $address=CustomerAddress::where('delivery_active',1)
            ->where('user_id',$user->id)
            ->first();

        foreach($cart as $item) {
            if($item->type=='subscription'){
                $total_cost=$total_cost+$item->quantity*($item->product->price??0)*$item->no_of_days;
                $savings=$savings+$item->quantity*(($item->product->price??0)-($item->product->cut_price))*$item->no_of_days;
            }
            else{
                $total_cost=$total_cost+$item->quantity*$item->no_of_days;
                $savings=$savings+$item->quantity*($item->product->price??0)*$item->no_of_days;
            }

            $items[]=new OrderDetail(array_merge($item->only('product_id', 'quantity','type','start_date','time_slot_id','no_of_days', 'total_quantity'), ['price'=>$item->product->price, 'cut_price'=>$item->product->cut_price]));

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
            'savings'=>$savings,
            'address_id'=>$address->id
        ]);

        $order->details()->saveMany($items);
        foreach($order->details as $d){
            if($d->type=='subscription')
                $d->days()->sync($days[$d->product_id]);
        }

        $order=Order::with('details.product.subcategory')->find($order->id);

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

        //$eligible_cashback=Wallet::calculateEligibleCashback($total_cost, $walletpoints);
        $eligible_goldcash=0;
        foreach($order->details as $d){
            if($d->type=='subscription')
                $eligible_goldcash=$eligible_goldcash+($d->price*$d->product->eligible_goldcash/100)*$d->quantity*$d->no_of_days;
            else
                $eligible_goldcash=$eligible_goldcash+($d->price*$d->product->eligible_goldcash/100)*$d->quantity;
        }

        $order->use_points=true;
        if($eligible_goldcash >= $order->total_cost+$order->delivery_charge - $order->coupon_discount-$order->balance_used){
            $order->points_used=$order->total_cost+$order->delivery_charge-$order->coupon_discount;
        }else{
            $order->points_used=$eligible_goldcash;
        }
        return $order->points_used;
    }


    public function orderdetails(Request $request, $id){

        $user=$request->user;

        $order=Order::with('details.product', 'details.days', 'details.timeslot', 'deliveryaddress')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $items=[
            'subscriptions'=>[],
            'once'=>[]
        ];

        $total=0;
        $quantity=0;
        $price_total=0;
        $price_total_discount=0;

        foreach($order->details as $c){

            if($c->type=='subscription'){

                $total=$total+($c->price??0)*$c->quantity*$c->no_of_days;
                $quantity=$quantity+$c->quantity*$c->no_of_days;
                $price_total=$price_total+($c->price??0)*$c->quantity*$c->no_of_days;
                $price_total_discount=$price_total_discount+(($c->cut_price??0)-($c->price??0))*$c->quantity*$c->no_of_days;

                if($c->status=='pending'){
                    $show_cancel=$c->total_quantity>$c->delivered_quantity?1:0;
                    $show_edit=$c->total_quantity>$c->scheduled_quantity?1:0;
                    $initial_text='Starting On';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');

                }else if(in_array($c->status, ['partially-completed', 'completed'])){
                    $show_cancel=0;
                    $show_edit=0;
                    $initial_text='Started On';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');
                }else{
                    $show_cancel=0;
                    $show_edit=0;
                    $initial_text='Cancelled';
                    $time='';
                }

                $items['subscriptions'][]=array(
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
                    'price'=>$c->price,
                    'cut_price'=>$c->cut_price,
                    'date_text'=>$time,
                    'show_cancel'=>$show_cancel,
                    'show_edit'=>$show_edit,
                    'initial_text'=>$initial_text
                );
            }else{
                $total=$total+($c->price??0)*$c->quantity;
                $quantity=$quantity+$c->quantity;
                $price_total=$price_total+($c->price??0)*$c->quantity;
                $price_total_discount=$price_total_discount+(($c->cut_price??0)-($c->price??0))*$c->quantity;

                if($c->status=='pending'){
                    $show_cancel=$c->total_quantity>$c->delivered_quantity?1:0;
                    $show_edit=$c->total_quantity>$c->scheduled_quantity?1:0;
                    $initial_text='Arriving By';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');

                }else if(in_array($c->status, ['partially-completed', 'completed'])){
                    $show_cancel=0;
                    $show_edit=0;
                    $initial_text='Delivered At';
                    $time=date('d M', strtotime($c->last_delivery_at)).' '.(isset($c->last_delivery_at)?date('h:ia', strtotime($c->last_delivery_at)):'');
                }else{
                    $show_cancel=0;
                    $show_edit=0;
                    $initial_text='Cancelled';
                    $time='';
                }

                $items['once'][]=array(
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
                    //    'discount'=>$c->sizeprice->discount,
                    'price'=>$c->price,
                    'cut_price'=>$c->cut_price,
                    'date_text'=>$time,
                    'show_cancel'=>$show_cancel,
                    'show_edit'=>$show_edit,
                    'initial_text'=>$initial_text,
                    'status'=>$c->status
                );
            }
        }


        return [
            'deliveryaddress'=>$order->deliveryaddress,
            'items'=>$items,
            'total'=>$order->total_cost,
            'delivery_charge'=>$order->delivery_charge,
            'coupon'=>$order->coupon??'',
            'coupon_discount'=>$order->coupon_discount,
            'grand_total'=>$order->total_cost+$order->delivery_charge-$order->coupon_discount,
            'balance_used'=>$order->balance_used,
            'points_used'=>$order->points_used,
            'payble'=>$order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->ballance_used=$order->points_used,
            'savings'=>round($order->savings+$order->coupon_discount, 2),
        ];


    }


    public function cancel(Request $request, $detail_id){

        $request->validate([
           'reason'=>'required|string|max:250'
        ]);

        $detail=OrderDetail::
            whereHas('order', function($order){
                $order->where('status', 'confirmed');
            })
            ->where('status', 'pending')
            ->findOrFail($detail_id);

        if($detail->type=='subscription')
            return $this->cancelSubscription($detail, $request->reason);
        else
            return $this->cancelOnce($detail, $request->reason);

    }


    private function cancelOnce($detail, $message){
        $order=Order::with('details', function($details) use($detail){
            $details->with('product.subcategory')
            ->where('order_details.id', '!=', $detail->id);
        })
        ->find($detail->order_id);

        $itemcost=$detail->quantity*$detail->price;

        if($order->coupon){
            $coupon=Coupon::where('code', $order->coupon)->first();
            $discount=$order->getCouponDiscount($coupon);
            if($order->coupon_discount-$discount >= $itemcost)
                return [
                    'status'=>'failed',
                    'message'=>'This item cannot be cancelled due to heavy coupon discounts applied.'
                ];
            $refund_amount= $itemcost - ($order->coupon_discount-$discount);

            if($discount>0){
                $order->applyCoupon($coupon);
            }else{
                $order->coupon=null;
                $order->coupon_discount=0;
            }
            $order->total_cost=$order->total_cost-$itemcost;
            $order->save();

            $detail->status='cancelled';
            $detail->remark=$message;
            $detail->save();

            $detail->deliveries()
                ->where('status', 'pending')
                ->update(['status'=>'cancelled']);

            //Refund Amount to Wallet
            if($refund_amount>0)
                Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$refund_amount, 'CASH', $order->id);

            event(new ItemCancelled($order, $detail));

            return [
                'status'=>'success',
                'message'=>'Item has been cancelled',
                'order_id'=>$detail->order_id,
            ];
        }

        //goldcash %tage in total amount
        if($order->points_used>0){
            $percent=$order->total_cost*100/$order->points_used;
        }else{
            $percent=0;
        }

        $refund_amount=$itemcost;
        $point_return=round($refund_amount*$percent/100, 2);
        $cash_return=round($order->total_cost-$point_return, 2);

        $order->total_cost=$order->total_cost-$itemcost;
        $order->save();

        $detail->status='cancelled';
        $detail->remark=$message;
        $detail->save();

        $detail->deliveries()
            ->where('status', 'pending')
            ->update(['status'=>'cancelled']);

        //Refund Amount to Wallet
        if($cash_return>0)
            Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$cash_return, 'CASH', $order->id);

        if($point_return>0)
        Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$point_return, 'POINT', $order->id);

        return [
            'status'=>'success',
            'message'=>'Item has been cancelled',
            'order_id'=>$detail->order_id,
        ];
    }


    private function cancelSubscription($detail,$message){

        if($detail->product->subscription_cashback)
            return [
                'status'=>'failed',
                'message'=>'This subscription cannot be cancelled due to additional gold cash benefits'
            ];

        $order=Order::with('details.product.subcategory')
            ->find($detail->order_id);

        foreach($order->details as $o)
            if($o->id==$detail->id){
                $itemcost=($o->total_quantity-$o->delivered_quantity)*$o->price;
                $o->total_quantity=$o->delivered_quantity;
            }

        //$itemcost=$detail->total_quantity*$detail->price;

        if($order->coupon){
            $coupon=Coupon::where('code', $order->coupon)->first();
            $discount=$order->getCouponDiscount($coupon);
            if($order->coupon_discount-$discount >= $itemcost)
                return [
                    'status'=>'failed',
                    'message'=>'This item cannot be cancelled due to heavy coupon discounts applied.'
                ];

            $refund_amount= $itemcost - ($order->coupon_discount-$discount);


            if($discount>0){
                $order->applyCoupon($coupon);
            }else{
                $order->coupon=null;
                $order->coupon_discount=0;
            }
            $order->total_cost=$order->total_cost-$itemcost;
            $order->save();

            $detail->total_quantity=$detail->delivered_quantity;
            $detail->remark=$message;
            $detail->status='cancelled';
            $detail->save();

            $detail->deliveries()
                ->where('status', 'pending')
                ->update(['status'=>'cancelled']);

            //Refund Amount to Wallet
            if($refund_amount>0)
                Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$refund_amount, 'CASH', $order->id);

            return [
                'status'=>'success',
                'message'=>'Subscription has been cancelled',
                'order_id'=>$detail->order_id,
            ];
        }

        //goldcash %tage in total amount
        if($order->points_used>0){
            $percent=$order->total_cost*100/$order->points_used;
        }else{
            $percent=0;
        }

        $refund_amount=$itemcost;
        $point_return=round($refund_amount*$percent/100, 2);
        $cash_return=round($order->total_cost-$point_return, 2);

        $order->total_cost=$order->total_cost-$itemcost;
        $order->save();

        $detail->status='cancelled';
        $detail->remark=$message;
        $detail->save();

        $detail->deliveries()
            ->where('status', 'pending')
            ->update(['status'=>'cancelled']);

        //Refund Amount to Wallet
        if($cash_return>0)
            Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$cash_return, 'CASH', $order->id);

        if($point_return>0)
            Wallet::updatewallet($order->user_id, 'Refund for item cancellation from order id: '.$order->refid, 'Credit',$point_return, 'POINT', $order->id);

        return [
            'status'=>'success',
            'message'=>'Subscription has been cancelled',
            'order_id'=>$detail->order_id,
        ];
    }


    public function getSchedule(Request $request, $item_id){

        $item=OrderDetail::with(['days'])
                ->where('status', 'pending')
                ->findOrFail($item_id);

        $timeslots=TimeSlot::active()
            ->get();

        $header_message='The schedule changes will be effective after 12 hrs.';

        return [
            'status'=>'success',
            'data'=>compact('item', 'timeslots', 'header_message')
        ];

    }

    public function reschedule(Request $request, $item_id){

        $item=OrderDetail::with(['product', 'days'])
            ->where('status', 'pending')
            ->findOrFail($item_id);

        if($item->type=='subscription'){
            $request->validate([
                'time_slot'=>'required|integer',
                'quantity'=>'required|integer',
                'days'=>'required|array',
                'days.*'=>'nullable|min:0|max:6'
            ]);

            if($request->quantity > $item->total_quantity-$item->scheduled_quantity)
                return [
                    'status'=>'failed',
                    'message'=>'Quantity exceeds from available quantity'
                ];

            TimeSlot::findOrFail($request->time_slot);

            $item->quantity=$request->quantity;
            $item->time_slot_id=$request->time_slot;
            $item->save();

            if(!empty($request->days)){
                $seldays=[];
                foreach($request->days as $d){
                    if($d!=='' ){
                        $seldays[]=$d;
                        $item->days()->sync($seldays);
                    }
                }

            }

            return [
                'status'=>'success',
                'message'=>'Item schedule has been updated',
                'order_id'=>$item->order_id,
            ];

        }else{
            $request->validate([
                'start_date'=>'required|date_format:Y-m-d',
                'time_slot'=>'required|integer',
            ]);

            $ts=TimeSlot::findOrFail($request->time_slot);
            if(!$ts->checkTimings($request->date)){
                $next=TimeSlot::getNextDeliverySlot();
                return [
                    'status'=>'failed',
                    'message'=>'Next earliest delivery time is '.$next['name'].' '.$next['time']
                ];
            }

            $item->start_date=$request->start_date;
            $item->time_slot_id=$request->time_slot;
            $item->save();

            return [
                'status'=>'success',
                'message'=>'Item schedule has been updated',
                'order_id'=>$item->order_id,
            ];

        }

    }

}
