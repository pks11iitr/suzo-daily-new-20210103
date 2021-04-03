<?php

namespace App\Http\Controllers\MobileApps\Rider\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyDelivery;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function openDeliveries(Request $request){

        $user=$request->user;
        $deliveriesobj=DailyDelivery::with(['product'=>function($product){
                            $product->select('id', 'name', 'image');
                        },'deliveryaddress', 'order'=>function($order){
                            $order->select('id','refid');
                        },'timeslot'=>function($ts){
            $ts->select('id','name');
        },'area'])
            ->where("status", 'pending')
            ->where('rider_id', $user->id)
            ->orderBy('area_id', 'asc')
            ->get();

        $deliveries=[];
        foreach ($deliveriesobj as $del){
            $deliveries[]=[
                'id'=>$del->id,
                'delivery_id'=>($del->order->refid??'').'/'.$del->id,
                'product_name'=>$del->product->name??'',
                'product_image'=>$del->product->image??'',
                'quantity'=>$del->quantity,
                'date'=>$del->delivery_date,
                'time'=>$del->timeslot->name??'',
                'deliveryaddress'=>$del->deliveryaddress,
                'area'=>$del->area
            ];
        }


        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];

    }

    public function updateDeliveryStatus(Request $request, $id){

        $request->validate([
            'status'=>'required|in:delivered,delivery-failed,partially-delivered,returned'
        ]);

        $user=$request->user;

        $comment=$request->message;
        $status=$request->status;

        $delivery=DailyDelivery::with(['customer', 'order'])
        ->where('rider_id', $user->id)
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

        //$user->notify(new FCMNotification(''))

        return [
            'status'=>'success',
            'message'=>'Status has been Updated'
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
                'id'=>$del->id,
                'delivery_id'=>($del->order->refid??'').'/'.$del->id,
                'product_name'=>$del->product->name??'',
                'product_image'=>$del->product->image??'',
                'quantity'=>$del->quantity,
                'date'=>$del->delivery_date,
                'time'=>$del->timeslot->name??'',
                'deliveryaddress'=>$del->deliveryaddress,
                'status'=>$del->status,
                'area'=>$del->area

            ];
        }


        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];

    }


}
