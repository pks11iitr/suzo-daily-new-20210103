<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyDelivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request, $detail_id){
        $deliveriesobj=DailyDelivery::with(['product', 'detail'])
            ->whereHas('detail', function($detail){
                $detail->where('type', 'subscription');
            })
            ->where('detail_id', $detail_id)
            ->orderBy('id', 'desc')
            ->get();

        $delivery_arr=[];
        foreach($deliveriesobj as $d){
            if(!isset($delivery_arr[date('M Y', strtotime($d->delivered_at))]))
                $delivery_arr[date('M Y', strtotime($d->delivered_at))]=[];
            $delivery_arr[date('M Y', strtotime($d->delivered_at))][]=[
                'day'=>date('d', strtotime($d->delivered_at)),
                'weekday'=>date('D', strtotime($d->delivered_at)),
                'units'=>$d->quantity.' Units',
                'status'=>$d->status,
                'remark'=>$d->remark??''
            ];
        }

        $deliveries=[];
        foreach($delivery_arr as $key=>$val){
            $deliveries=[
                'month'=>$key,
                'daywise'=>$val
            ];
        }

        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];
    }
}
