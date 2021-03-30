<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\Notification\FCMNotification;
use App\Services\Payment\RazorPayService;
use Illuminate\Http\Request;

class MemberShipController extends Controller
{
    public function __construct(RazorPayService $pay)
    {
        $this->pay=$pay;
    }

    public function index(Request $request){

        $membershipobj=Membership::active()->get();
        $memberships=[];

        foreach($membershipobj as $membership){
            $memberships[]=[
                'id'=>$membership->id,
                'price'=>$membership->price,
                'cut_price'=>$membership->cut_price,
                'title'=>$membership->title,
                'description'=>explode('***', $membership->description),
                'validity'=>$membership->months,
            ];
        }

        $active_membership=1;

        return [
            'status'=>'success',
            'data'=>compact('memberships', 'active_membership')
        ];

    }


    public function subscribe(Request $request, $id){

        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        if($user->isMembershipActive() && $user->membership_expiry>=date('Y-m-d')){
            return [
                'status'=>'failed',
                'message'=>'You already have a active subscription'
            ];
        }


        $membership=Membership::active()->find($id);
        if(!$membership)
            return [
                'status'=>'success',
                'message'=>'This Plan does not exist'
            ];

        $subscription=Subscription::create([

            'user_id'=>$user->id,
            'plan_id'=>$membership->id,
            'refid'=>env('MACHINE_ID').time()

        ]);

        $response=$this->pay->generateorderid([
            "amount"=>($membership->price)*100,
            "currency"=>"INR",
            "receipt"=>$subscription->refid,
        ]);
        $responsearr=json_decode($response);
        //var_dump($responsearr);die;
        if(isset($responsearr->id)){
            $subscription->razorpay_order_id=$responsearr->id;
            $subscription->razorpay_order_id_response=$response;
            $subscription->save();
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>[
                    'payment_done'=>'no',
                    'razorpay_order_id'=> $subscription->razorpay_order_id,
                    'total'=>($membership->price)*100,
                    'email'=>$user->email,
                    'mobile'=>$user->mobile,
                    'description'=>'Membership Subscription at HalloBasket',
                    //'name'=>$order->name,
                    'currency'=>'INR',
                    'merchantid'=>$this->pay->merchantkey,
                ],
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'Payment cannot be initiated',
                'data'=>[
                ],
            ];
        }

    }

    public function verify(Request $request){

        $request->validate([
            'razorpay_order_id'=>'required',
            'razorpay_signature'=>'required',
            'razorpay_payment_id'=>'required'

        ]);

        $subscription=Subscription::where('razorpay_order_id', $request->razorpay_order_id)
            ->first();

        if(!$subscription)
            return [
                'status'=>'failed',
                'message'=>'Invalid Operation Performed'
            ];

        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult) {

            $user=Customer::find($subscription->user_id);
            $memberships=Membership::active()->find($subscription->plan_id);

            $subscription->is_confirmed = true;
            $subscription->razorpay_payment_id = $request->razorpay_payment_id;
            $subscription->razorpay_payment_id_response = $request->razorpay_signature;
            $subscription->save();

            $user->membership_id=$subscription->plan_id;
            $user->membership_expiry=date('Y-m-d', strtotime('+'.$memberships->months.' months'));
            $user->save();


            $title='Membership Subscription Confirmed';
            $message='Congratulations! Your subscription at Frestr is successful';

//            Notification::create([
//                'user_id'=>$subscription->user_id,
//                'title'=>$title,
//                'description'=>$message,
//                'data'=>null,
//                'type'=>'individual'
//            ]);
//
//            if($subscription->customer->notification_token??null)
//                FCMNotification::sendNotification($subscription->customer->notification_token, $title, $message);


            return [
                'status'=>'success',
                'message'=> $message,
                'data'=>[
                    'ref_id'=>$subscription->refid,
                    'order_id'=>$subscription->id
                ]
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'We apologize, Your payment cannot be verified',
                'data'=>[

                ],
            ];
        }
    }
}
