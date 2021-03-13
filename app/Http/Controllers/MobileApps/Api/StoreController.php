<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use DB;

class StoreController extends Controller
{
    public function index(Request $request){
        $latitude=   $request->lat??'28.56834';
        $longitude= $request->lang??'77.56834';

        $stores = Store::active()->select(DB::raw('*, ROUND(( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lang ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( stores.lat ) ) ) ),2) AS distance'))
            ->orderBy('distance', 'ASC')
            ->get();
       // $stores=Store::active()->get();
        if($stores->count()>0){
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>compact('stores')
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'No Record Found',
            ];
        }
    }

    public function details(Request $request,$id){

        if(empty($id)){
            return [
                'status'=>'failed',
                'message'=>'Store Id parameter Missing',
            ];
        }
        $stores_details=Store::active()->with('images')->where('id',$id)->first();
        if($stores_details){
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>compact('stores_details')
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'Some error found',
            ];
        }
    }
}
