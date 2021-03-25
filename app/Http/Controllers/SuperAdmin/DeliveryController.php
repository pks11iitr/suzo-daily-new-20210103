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
            $deliveries = $deliveries->where('delivery_date', '>=', $request->fromdate);

        if(isset($request->todate))
            $deliveries = $deliveries->where('delivery_date', '<=', $request->todate);

        if($request->rider_id)
            $deliveries=$deliveries->where('rider_id', $request->rider_id);

        $deliveries =$deliveries->orderBy('id', 'desc')->paginate(20);


        $riders = Rider::active()->get();

        return view('admin.deliveries.index',['deliveries'=>$deliveries,'riders'=>$riders]);

    }
}
