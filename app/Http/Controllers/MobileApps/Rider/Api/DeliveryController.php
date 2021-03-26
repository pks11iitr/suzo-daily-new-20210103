<?php

namespace App\Http\Controllers\MobileApps\Rider\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyDelivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function openDeliveries(Request $request){

        $user=$request->user;
        $deliveriesobj=DailyDelivery::with(['product'=>function($product){
                            $product->select('id', 'name');
                        },'deliveryaddress', 'order'=>function($order){
                            $order->select('id','refid');
                        },'timeslot'=>function($ts){
            $ts->select('id','name');
        }])
            ->where("status", 'pending')
            ->where('rider_id', $user->id)
            ->orderBy('area_id', 'asc')
            ->get();

        $deliveries=[];
        foreach ($deliveriesobj as $del){
            $deliveries[]=[
                'delivery_id'=>($del->order->refid??'').'/'.$del->id,
                'product_name'=>$del->product->name??'',
                'quantity'=>$del->quantity,
                'date'=>$del->delivery_date,
                'time'=>$del->timeslot->name??'',
                'deliveryaddress'=>$del->deliveryaddress,
            ];
        }


        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];

    }

    public function updateDeliveryStatus(Request $request, $id){

        $request->validate([
            'status'=>'required|in:delivered, delivery-failed, partially-delivered, returned'
        ]);

        $user=$request->user;

        $comment=$request->message;
        $status=$request->status;

        $delivery=DailyDelivery::where('rider_id', $user->id)
        ->find($id);

        if(!$delivery)
            return [
                'status'=>'failed',
                'message'=>'Invalid Request'
            ];

        if($delivery->status!='pending')
        {
            if($delivery->status=='cancelled'){
                return [
                    'status'=>'failed',
                    'message'=>'This delivery has been cancelled'
                ];
            }
            return [
                'status'=>'status',
                'message'=>'This delivery cannot be updated'
            ];
        }


        $delivery->comment=$comment;
        $delivery->status=$status;
        $delivery->save();

        return [
            'status'=>'success',
            'message'=>'Delivered'
        ];

    }


    public function pastDeliveries(Request $request){

        $user=$request->user;
        $deliveriesobj=DailyDelivery::with(['product'=>function($product){
            $product->select('id', 'name');
        },'deliveryaddress', 'order'=>function($order){
            $order->select('id','refid');
        },'timeslot'=>function($ts){
            $ts->select('id','name');
        }])
            ->where("status", '!=', 'pending')
            ->where('rider_id', $user->id)
            ->orderBy('area_id', 'asc')
            ->get();

        $deliveries=[];
        foreach ($deliveriesobj as $del){
            $deliveries[]=[
                'delivery_id'=>($del->order->refid??'').'/'.$del->id,
                'product_name'=>$del->product->name??'',
                'quantity'=>$del->quantity,
                'date'=>$del->delivery_date,
                'time'=>$del->timeslot->name??'',
                'deliveryaddress'=>$del->deliveryaddress,
                'status'=>$del->status
            ];
        }


        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];

    }


}
