<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\ItemCancelled;
use App\Events\ItemRescheduled;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\ReturnRequest;
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

        $address=CustomerAddress::where('delivery_active',1)
            ->where('user_id',$user->id)
            ->first();
        if(!$address)
            return [
                'status'=>'failed',
                'message'=>'Please add an address'
            ];

        $cart=Cart::with('days', 'timeslot', 'product')
            ->where('user_id', $user->id)
            ->get();

        $delivery=Configuration::where('param', 'delivery_charge')->first();

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

                if($user->membership_expiry>=$item->start_date){
                    $subscription_days=$item->days->map(function($element){
                        return $element->id;
                    })->toArray();
                    $count_free_days=calculateDaysCountBetweenDate($item->start_date, $user->membership_expiry, $subscription_days);
                    $delivery_charge=$delivery_charge+($item->product->delivery_charge*$item->total_quantity)-$item->quantity*$item->product->delivery_charge*$count_free_days;
                }else{
                    $delivery_charge=$delivery_charge+($item->product->delivery_charge*$item->total_quantity);
                }

            }
            else{
                $total_cost=$total_cost+$item->quantity*($item->product->price??0);
                $savings=$savings+$item->quantity*(($item->product->price??0)-($item->product->cut_price));

                if(!isset($daywise_delivery_total))
                    $daywise_delivery_total[$item->start_date]=0;
                $daywise_delivery_total[$item->start_date]=$daywise_delivery_total[$item->start_date]+$item->product->price*$item->quantity;

            }

            $items[]=new OrderDetail(array_merge($item->only('product_id', 'quantity','type','start_date','time_slot_id','no_of_days', 'total_quantity'), ['price'=>$item->product->price, 'cut_price'=>$item->product->cut_price]));

            $days[$item->product_id]=$item->days->map(function($elem){
                return $elem->id;
            })->toArray();

        }

        if(!empty($daywise_delivery_total)){
            foreach($daywise_delivery_total as $key=>$val){
                if($user->membership_expiry < $key && $val< 399){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }else if($user->membership_expiry >= $key && $val< 149){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }
            }
        }

        //chnage it to dynamic later
        //$delivery_charge=50;

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
            if($order->total_cost+$order->delivery_charge-$order->balance_used){
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

    private function usePoints($order){

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

        $order=Order::with('details.product', 'details.days', 'details.timeslot', 'deliveryaddress', 'details.returnrequests')
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
                    $show_return=0;
                    $initial_text='Starting On';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');

                }else if(in_array($c->status, ['partially-completed', 'completed'])){
                    $show_cancel=0;
                    $show_edit=0;
                    $show_return=0;
                    $initial_text='Started On';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');
                }elseif($c->status=='returned'){
                    $show_cancel=0;
                    $show_edit=0;
                    $show_return=0;
                    $initial_text='Returned';
                    $time='';
                }else{
                    $show_cancel=0;
                    $show_edit=0;
                    $show_return=0;
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
                    'show_return'=>$show_return,
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
                    $show_return=0;
                    $initial_text='Arriving By';
                    $time=date('d M', strtotime($c->start_date)).' '.(isset($c->timeslot->from_time)?date('h:ia', strtotime($c->timeslot->from_time)):'');

                }else if(in_array($c->status, ['partially-completed', 'completed'])){
                    $show_cancel=0;
                    $show_edit=0;
                    if(empty($c->returnrequests->toArray()) && date('Y-m-d H:i:s', strtotime('+2 days', strtotime($c->last_delivery_at))) > date('Y-m-d H:i:s'))
                        $show_return=0;
                    else
                        $show_return=1;
                    $initial_text='Delivered At';
                    $time=date('d M', strtotime($c->last_delivery_at)).' '.(isset($c->last_delivery_at)?date('h:ia', strtotime($c->last_delivery_at)):'');
                }elseif($c->status=='returned'){
                    $show_cancel=0;
                    $show_edit=0;
                    $show_return=0;
                    $initial_text='Returned';
                    $time='';
                }else{
                    $show_cancel=0;
                    $show_edit=0;
                    $show_return=0;
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
                    'show_return'=>$show_return,
                    'initial_text'=>$initial_text,
                    'status'=>$c->status
                );
            }
        }


        return [
            'deliveryaddress'=>$order->deliveryaddress,
            'items'=>$items,
            'total'=>$order->total_cost??0,
            'delivery_charge'=>$order->delivery_charge??0,
            'coupon'=>$order->coupon??'--',
            'coupon_discount'=>$order->coupon_discount,
            'grand_total'=>$order->total_cost+$order->delivery_charge-$order->coupon_discount,
            'balance_used'=>$order->balance_used,
            'points_used'=>$order->points_used,
            'payble'=>$order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->ballance_used=$order->points_used,
            'savings'=>round($order->savings+$order->coupon_discount, 2),
        ];


    }


    public function cancel(Request $request, $detail_id){

        $user=$request->user;
        $request->validate([
           'reason'=>'required|string|max:250'
        ]);

        $detail=OrderDetail::
            whereHas('order', function($order) use($user){
                $order->where('status', 'confirmed')
                    ->where('user_id', $user->id);
            })
            ->where('status', 'pending')
            ->findOrFail($detail_id);

        if($detail->type=='subscription')
            return $this->cancelSubscription($user, $detail, $request->reason);
        else
            return $this->cancelOnce($user, $detail, $request->reason);

    }


    private function cancelOnce($detail, $message){
        $order=Order::with(['details'=> function($details) use($detail){
            $details->with('product.subcategory')
            ->where('order_details.id', '!=', $detail->id);
        }])
        ->find($detail->order_id);

        $itemcost=$detail->quantity*$detail->price;

        if($order->coupon){
            $coupon=Coupon::where('code', $order->coupon)->first();
            $discount=$order->getCouponDiscount($coupon);
            if($order->coupon_discount-$discount >= $itemcost)
                return [
                    'status'=>'failed',
                    'message'=>'This order includes coupon discount of Rs. '.$order->coupon_discount.'. Cancellation of this item will cause cancellation of coupon discount, which will cost additional charges of Rs. '.($order->coupon_discount-$discount - $itemcost).'. If you still want to cancel this item please raise a case in complaint section.'
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

        event(new ItemCancelled($order, $detail));
        return [
            'status'=>'success',
            'message'=>'Item has been cancelled',
            'order_id'=>$detail->order_id,
        ];
    }


    private function cancelSubscription($detail,$message){

        if($detail->product->subscription_cashback){

            return [
                'status'=>'failed',
                'message'=>'This subscription includes additional benefit of Frestr Cash. Please raise a case in complaints section to cancel this product'
            ];

        }


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
                    'message'=>'This order includes coupon discount of Rs. '.$order->coupon_discount.'. Cancellation of this item will cause cancellation of coupon discount, which will cost additional charges of Rs. '.($order->coupon_discount-$discount - $itemcost).'. If you still want to cancel this item please raise a case in complaint section.'
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

            event(new ItemCancelled($order, $detail));

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

        event(new ItemCancelled($order, $detail));

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

        $user=$request->user;

        $item=OrderDetail::with(['product', 'days'])
            ->whereHas('order', function($order)use($user){
                $order->where('user_id', $user->id);
            })
            ->where('status', 'pending')
            ->findOrFail($item_id);

        if($item->type=='subscription'){
            $request->validate([
                'time_slot'=>'required|integer',
                'quantity'=>'required|integer|min:1',
                'days'=>'required|array',
                'days.*'=>'nullable|min:0|max:6'
            ]);

            if($request->quantity > $item->total_quantity-$item->scheduled_quantity)
                return [
                    'status'=>'failed',
                    'message'=>'Quantity exceeds from available quantity'
                ];

            if($request->quantity < $item->item_quantity && $user->membership_expiry >= $item->start_date){
                return [
                    'status'=>'failed',
                    'message'=>'This product was purchased under active membership with no delivery charge. Rescheduling it post membership expiry will cause additional charges. Please contact support if you still want to reschedule this'
                ];
            }

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

            event(new ItemRescheduled($item));

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
            if(!$ts->checkTimings($request->start_date)){
                $next=TimeSlot::getNextDeliverySlot();
                return [
                    'status'=>'failed',
                    'message'=>'Next earliest delivery time is '.$next['name'].' '.$next['time']
                ];
            }

            if($user->membership_expiry >= $item->start_date && $request->start_date > $user->membership_expiry ){
                return [
                    'status'=>'failed',
                    'message'=>'This product was purchased under active membership with no delivery charge. Rescheduling it post membership expiry will cause additional charges. Please contact support if you still want to reschedule it.'
                ];
            }

            $item->start_date=$request->start_date;
            $item->time_slot_id=$request->time_slot;
            $item->save();

            event(new ItemRescheduled($item));

            return [
                'status'=>'success',
                'message'=>'Item schedule has been updated',
                'order_id'=>$item->order_id,
            ];

        }

    }

    public function raiseReturn(Request $request, $id){
        $user=$request->user;

        $request->validate([
            'quantity'=>'required|integer',
            'return_reason'=>'required|max:500'
        ]);

        $detail=OrderDetail::whereHas('order', function($order) use($user){
            $order->where('user_id', $user->id);
        })->where('status', 'completed')
            ->where('type', 'once')
            ->findOrFail($id);

        if($detail->quantity > $request->quantity)
            return [
                'status'=>'failed',
                'message'=>'Return quantity cannot exceed purchased quantity'
            ];

        if(date('Y-m-d H:i:s', strtotime('+2 days', strtotime($detail->last_delivery_at))) > date('Y-m-d H:i:s')){
            return [
                'status'=>'failed',
                'message'=>'This product cannot be returned now'
            ];
        }


        ReturnRequest::updateOrCreate([
            'order_id'=>$detail->order_id,
            'delivery_id'=>$detail->deliveries[0]->id??0,
            'details_id'=>$detail->id,
            'product_id'=>$detail->product_id,
        ],[
            'quantity'=>$request->quantity,
            'return_reason'=>$request->return_reason,
            'price'=>$detail->price,
            'store_id'=>$detail->order->store_id,
            'user_id'=>$detail->order->user_id,
            'rider_id'=>$detail->order->rider_id,
            'return_type'=>'after-delivery'
        ]);

        return [
            'status'=>'success',
            'message'=>'Your return request has been submitted'
        ];

    }

}
