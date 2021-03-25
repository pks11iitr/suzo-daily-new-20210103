<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DailyDelivery;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request){
        $deliveries = DailyDelivery::where('id','>=',0);

        if(isset($request->fromdate))
            $deliveries = $deliveries->where('delivery_date', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $deliveries = $deliveries->where('delivery_date', '<=', $request->todate.' 23:59:59');

        if($request->user_id)
            $deliveries=$deliveries->where('user_id', $request->user_id);

        if($request->rider_id)
            $deliveries=$deliveries->where('rider_id', $request->rider_id);

        $deliveries =$deliveries->orderBy('id', 'desc')->paginate(20);


        $riders = Rider::active()->get();

        return view('admin.deliveries.index',['deliveries'=>$deliveries,'riders'=>$riders]);

    }
}
