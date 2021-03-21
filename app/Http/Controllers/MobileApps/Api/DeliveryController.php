<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyDelivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request, $item_id){
        $deliveries=DailyDelivery::with(['product', 'detail'])
            ->where('detail_id', $item_id)
            ->orderBy('id', 'desc')
            ->get();


        return [
            'status'=>'success',
            'data'=>compact('deliveries')
        ];
    }
}
