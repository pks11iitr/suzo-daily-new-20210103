<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ReturnProduct;
use App\Models\ReturnRequest;
use App\Models\Wallet;
use App\Services\Notification\FCMNotification;
use App\Services\SMS\Msg91;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReturnRequestController extends Controller
{
    public function index(Request $request){

        $returns=ReturnRequest::with(['size', 'product', 'order.details.entity'])
            //->has('details')
            ->orderBy('id', 'desc')
            ->paginate(10);


        foreach($returns as $return){
            if($return->status=='pending')
                $return->cost=$this->checkTotalAfterReturn($return);
            else
                $return->cost=$return->order->total_cost;
        }

        return view('admin.returnrequest.view',['returns'=>$returns]);

    }


    public function checkTotalAfterReturn($returnrequest){

        $items=[];
        $items[$returnrequest->details_id]=$returnrequest->quantity;
        $itemids=[$returnrequest->details_id];

        //return $itemids;
        $order=Order::with(['details'=>function($details)use($itemids){
            //$details->whereIn('details.id', $itemids);
        }])
            ->where('rider_id', $returnrequest->order->rider_id)
            ->find($returnrequest->order->id);

        if(!$order || empty($order->details->toArray()))
            return 0;

        //var_dump($itemids);
        //var_dump($request->items);
        //calculate return product total value
        $total_after_return=0;
        foreach($order->details as $item){
            //var_dump($request->items[$item->id]);
            if(isset($items[$item->id])){
                $total_after_return=$total_after_return+$item->price*($item->quantity-($items[$item->id]??0));
                $item->quantity=$item->quantity-($items[$item->id]??0);
                //echo '--aa--';
            }else{
                $total_after_return=$total_after_return+$item->price*$item->quantity;
                //echo '-bb--';
            }

        }

        //return $order->details;
        //die;
        //total cost after deduction
        return $total_cost=$total_after_return;

    }

//    public function details(Request $request,$id){
//        $orderdetails = ReturnRequest::findOrFail($id);
//        return view('admin.returnrequest.details',['orderdetails'=>$orderdetails]);
//    }

    public function cancelReturnRequest(Request $request,$return_id){
        $return=ReturnRequest::with(['order.customer', 'details.entity', 'order.storename', 'order.rider'])
            ->where('status', 'pending')
            ->findOrFail($return_id);
        $return->status='rejected';
        $return->remark=$request->reason;
        $return->save();

        if($return->order->customer->notification_token??'')
            FCMNotification::sendNotification($return->order->customer->notification_token, 'Return Rejected', $request->reason);

        //send customer notification
        if(isset($return->order->storename->mobile))
            Msg91::send($return->order->storename->mobile, 'Return has been rejected for Order ID:'.$return->order->refid.', Product: '.$return->details->entity->name??'', $request->reason, env('RETURN_REQUEST_REJECTED'));

        if(isset($return->order->rider->mobile))
            Msg91::send($return->order->rider->mobile, 'Return has been rejected for Order ID:'.$return->order->refid.', Product: '.$return->details->entity->name??'', $request->reason, env('RETURN_REQUEST_REJECTED'));


        return redirect()->back()->with('success', 'Return has been cancelled');
    }


    public function approveReturnProduct(Request $request, $return_id){

        $return=ReturnRequest::with('order.customer', 'details.entity', 'order.rider', 'order.storename')->where('status', 'pending')
            ->findOrFail($return_id);
        //$details=OrderDetail::findOrFail($return->detail_id);

        $items=[];
        $items[$return->details_id]=$return->quantity;

        $itemids=[$return->details_id];

        $order=Order::with(['details'=>function($details)use($itemids){
            $details->with(['entity', 'size']);
            //->whereIn('details.id', $itemids);
        }])
            ->where('status', 'completed')
            ->findOrFail($return->order_id);

        if(!$order || empty($order->details->toArray()))
            return abort(404);


        // Refund Balance to Wallet
        if($order->payment_mode=='COD'){
            //refund only for wallet balance used
            if($order->balance_used){
                Wallet::updatewallet($order->user_id, 'Refund For Order ID: '.$order->refid, 'Credit', $order->balance_used, 'CASH', $order->id);
            }
        }else{
            //refund complete paid amount
            if($order->total_cost-$order->coupon_discount-$order->points_used+$order->delivery_charge-$order->extra_amount){
                Wallet::updatewallet($order->user_id, 'Refund For Order ID: '.$order->refid, 'Credit',  ($order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->points_used-$order->extra_amount), 'CASH',$order->id);
            }
        }

        // Refund Cashback to Wallet
        if($order->points_used){
            Wallet::updatewallet($order->user_id, 'Refund For Order ID: '.$order->refid, 'Credit',  $order->points_used, 'POINT',$order->id);
        }
        //var_dump($items);
        $total_after_return=0;
        foreach($order->details as $item){
            //var_dump($request->items[$item->id]);
            if(isset($items[$item->id])){
                if($items[$item->id]>$item->quantity){
                    return [
                        'status'=>'failed',
                        'message'=>'Invalid Request'
                    ];
                }
                $total_after_return=$total_after_return+$item->price*($item->quantity-($items[$item->id]??0));
                $item->quantity=$item->quantity-($items[$item->id]??0);
                //echo '--aa--';
            }else{
                //echo 'a';
                $total_after_return=$total_after_return+$item->price*$item->quantity;
                //echo '-bb--';
            }

        }
        //echo $total_after_return;die;

        // Calculate new values
        $new_total_cost=$total_after_return;
        $new_delivery_charge=$order->delivery_charge;

        if($order->coupon_applied && $order->coupon_discount){
            $coupon=Coupon::where('code', $order->coupon_applied)->first();
            $new_coupon_discount=$order->getCouponDiscount($coupon);
        }else{
            $new_coupon_discount=0;
        }

        // make return product entries in database
        foreach($order->details as $d){

            if(in_array($d->id, $itemids)){
                ReturnProduct::create([

                    'order_id'=>$d->order_id,
                    'store_id'=>$order->store_id,
                    'rider_id'=>$order->rider_id,
                    'ref_id'=>$order->refid,
                    'entity_id'=>$d->entity_id,
                    'entity_type'=>$d->entity_type,
                    'size_id'=>$d->size_id,
                    'name'=>$d->name,
                    'image'=>$d->image,
                    'price'=>$d->price,
                    'cut_price'=>$d->cut_price,
                    'quantity'=>$items[$d->id],
                    'reason'=>$request->reason

                ]);

                Order::increaseItemCount($d, $items[$d->id]*$d->size->consumed_units);

                if($d->quantity==0)
                    $d->delete();
                else{
                    $d->save();
                }
            }

        }

        //refund when total goes to 0
        if($new_total_cost==0){
            $order->total_cost=0;
            $order->coupon_discount=0;
            $order->delivery_charge=0;
            $order->balance_used=0;
            $order->points_used=0;
            //$order->status='completed';
            $order->save();

            return [
                'status'=>'success',
                'message'=>'Items Has Been returned'
            ];
        }

        // Set New Values To Order
        $order->total_cost=$new_total_cost;
        $order->coupon_discount=$new_coupon_discount;
        $order->delivery_charge=$new_delivery_charge;
        //$order->use_balance=0;
        $order->balance_used=0;
        //$order->use_points=0;
        $order->points_used=0;

        $this->returnFromPaidOrder($order);

        $return->status='approved';
        $return->save();

        //send customer notification
        if(isset($return->order->storename->mobile))
            Msg91::send($return->order->storename->mobile, 'Return has been approved for Order ID:'.$return->order->refid.', Product: '.$return->details->entity->name??'', $request->reason, env('RETURN_REQUEST_APPROVED'));

        if(isset($return->order->rider->mobile))
            Msg91::send($return->order->rider->mobile, 'Return has been approved for Order ID:'.$return->order->refid.', Product: '.$return->details->entity->name??'', $request->reason, env('RETURN_REQUEST_APPROVED'));

        return redirect()->back()->with('success', 'Return has been approved');

    }

    public function returnFromPaidOrder($order){
        //Get Wallet Balances
        $balance=Wallet::balance($order->user_id);
        $points=Wallet::points($order->user_id);

        // final amount to be paid
        $payble_amount=$order->total_cost-$order->coupon_discount+$order->delivery_charge;

        // pay using cashback
        if($payble_amount > 0 && $points > 0 && $order->points){
            if($payble_amount <= $points){
                $cashback_consumed=$payble_amount;
                Wallet::updatewallet($order->user_id, 'Cashback deducted for Order ID: '.$order->refid, 'Debit', $cashback_consumed, 'POINT', $order->id);
            }else{
                $cashback_consumed=$points;
                Wallet::updatewallet($order->user_id, 'Cashback deducted for Order ID: '.$order->refid, 'Debit', $cashback_consumed, 'POINT', $order->id);
            }
            $order->points_used=$cashback_consumed;
            $order->use_points=1;
            $payble_amount=$payble_amount - $cashback_consumed;
        }

        // pay using wallet balance
        if($payble_amount > 0 && $balance > 0 && $order->use_balance){
            // deduct from pending amout + delivery charge from balance
            if($payble_amount <= $balance){
                $balance_consumed=$payble_amount;
                Wallet::updatewallet($order->user_id, 'Amount deducted for Order ID: '.$order->refid, 'Debit', $balance_consumed, 'CASH', $order->id);
            }else{
                $balance_consumed=$balance;
                Wallet::updatewallet($order->user_id, 'Amount deducted for Order ID: '.$order->refid, 'Debit', $balance_consumed, 'CASH', $order->id);
            }
            $order->use_balance=1;
            $order->balance_used=$balance_consumed;
            $payble_amount=$payble_amount - $balance_consumed;
        }

        $order->extra_amount=$payble_amount;
        $order->save();

        return $payble_amount;
    }



}
