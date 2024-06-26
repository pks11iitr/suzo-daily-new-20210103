<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\Area;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CustomerAddressController extends Controller
{

    public function getcustomeraddress(Request $request){

        $user=auth()->guard('customerapi')->user();

        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        $customeraddress=CustomerAddress::with('area')->where('user_id',$user->id)
            ->orderBy('delivery_active', 'desc')
            ->orderBy('id', 'desc')
            ->get();

       if($customeraddress->count()>0) {
           return [
               'message' => 'success',
               'data'=>$customeraddress
           ];
       }else{
           return [
               'message' => 'Please add an address'
           ];
       }

    }
    public function addcustomeraddress(Request $request){

        $request->validate([
            'first_name'=>'required',
            //'last_name'=>'required',
            'mobile_no'=>'required',
            'house_no'=>'required',
            'street'=>'required',
            'area_id'=>'required',
            'city'=>'required',
            'pincode'=>'required',

        ]);
        $user=auth()->guard('customerapi')->user();

        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];


        $customeraddress =  CustomerAddress::create([
                    'user_id'=>$user->id,
                    'first_name'=>ucfirst($request->first_name),
                    'last_name'=>ucfirst($request->last_name),
                    'mobile_no'=>$request->mobile_no,
                    'email'=>$request->email?:'',
                    'house_no'=>ucfirst($request->house_no),
                    'floor'=>ucfirst($request->floor),
                    'appertment_name'=>ucfirst($request->appertment_name),
                    'street'=>ucwords($request->street),
                    'landmark'=>ucwords($request->landmark?:''),
                    'area_id'=>$request->area_id,
                    'city'=>ucfirst($request->city),
                    'pincode'=>$request->pincode,
                    'lat'=>$request->lat?$request->lat:'',
                    'lang'=>$request->lang?$request->lang:'',
                    //'map_address'=>$request->map_address?$request->map_address:'',
                ]);

        if($customeraddress) {

            CustomerAddress::where('user_id',$user->id)->update(['delivery_active'=>0]);

            CustomerAddress::where('user_id', $user->id)
                ->where('id', $customeraddress->id)
                ->update(['delivery_active'=>1]);

            return [
                'status'=>'success',
                'message' => 'success',
            ];
        }else{
            return [
                'status'=>'failed',
                'message' => 'error'
            ];
        }

    }

//    public function addressupdate(Request $request,$id){
//
//        $request->validate([
//            'first_name'=>'required',
//            'last_name'=>'required',
//            'mobile_no'=>'required',
//            'house_no'=>'required',
//            'appertment_name'=>'required',
//            'street'=>'string|nullable',
//            'area'=>'required',
//            'city'=>'required',
//            'pincode'=>'required',
//            'address_type'=>'required',
//            'lat'=>'string|nullable',
//            'lang'=>'string|nullable',
//            'map_address'=>'string|nullable'
//        ]);
//
//        $user=auth()->guard('customerapi')->user();
//
//        if(!$user)
//            return [
//                'status'=>'failed',
//                'message'=>'Please login to continue'
//            ];
//
//        $customeraddress = CustomerAddress::find($id);
//        $customeraddress = $customeraddress->update($request->only(
//            'first_name','last_name','mobile_no','email','house_no','appertment_name','street','landmark','area','city','pincode','address_type','other_text','lat','lang', 'map_address'));
//
//        if($customeraddress) {
//            return [
//                'status'=>'success',
//                'message' => 'updated successfully',
//            ];
//        }else{
//            return [
//                'status'=>'failed',
//                'message' => 'error'
//            ];
//        }
//
//    }
//
//    public function getaddressdetail(Request  $request,$id){
//
//        $user=auth()->guard('customerapi')->user();
//
//        if(!$user)
//            return [
//                'status'=>'failed',
//                'message'=>'Please login to continue'
//            ];
//
//        $customeraddress = CustomerAddress::find($id);
//
//        if($customeraddress) {
//            return [
//                'status'=>'success',
//                'message' => 'successfully',
//                'data' => compact('customeraddress'),
//            ];
//        }else{
//            return [
//                'status'=>'failed',
//                'message' => 'error'
//            ];
//        }
//    }

    public function deliveryaddressactive(Request  $request,$id){

        $user=auth()->guard('customerapi')->user();

        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        CustomerAddress::where('user_id',$user->id)
            ->update(['delivery_active'=>0]);

        CustomerAddress::where('user_id', $user->id)
            ->where('id', $id)->first()
            ->update(['delivery_active'=>1]);

        return [
            'status'=>'success',
            'message' => 'successfully',
        ];

    }


}
