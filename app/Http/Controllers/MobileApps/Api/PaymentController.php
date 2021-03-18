<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\OrderConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\LogData;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Wallet;
use App\Services\Payment\RazorPayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct(RazorPayService $pay){
        $this->pay=$pay;
    }

    public function getPaymentInfo(Request $request, $order_id){
        $user=$request->user;

        $order=Order::where('user_id', $user->id)
            ->findOrFail($order_id);
        $disable_cod='no';
        if($order->total_cost+$order->delivery_charges-$order->coupon_discount-$order->cashback_used-$order->balance_used==0){
            $disable_cod='yes';
        }
        $payment_info=[
            'total'=>$order->total_cost,
            'delivery_charge'=>$order->delivery_charges,
            'coupon_discount'=>$order->coupon_discount,
            'wallet_balance'=>$order->balance_used,
            'gold_cash'=>$order->points_used,
            'to_be_paid'=>$order->total_cost+$order->delivery_charges-$order->coupon_discount-$order->cashback_used-$order->balance_used,
            'savings'=>$order->savings,
            'disable_cod'=>$disable_cod
        ];

        return [
          'status'=>'success',
          'data'=>compact('payment_info')
        ];

    }


    public function initiatePayment(Request $request, $id){
        $user=$request->user;
        $order=Order::where('user_id', $user->id)->findOrFail($id);

        $wallet=Wallet::walletdetails($user->id);

        if($wallet['balance'] < $order->balance_used){
            return [
                'status'=>'failed',
                'message'=>'Something went wrong. Please try again'
            ];
        }

        if($wallet['cashback'] < $order->points_used){
            return [
                'status'=>'failed',
                'message'=>'Something went wrong. Please try again'
            ];
        }

        if(!empty($order->coupon)){
            $coupon=Coupon::active()->where('code', $order->coupon_applied)->first();
            if(!$coupon){
                return [
                    'status'=>'failed',
                    'message'=>'Invalid Coupon'
                ];
            }
            if($coupon && !$coupon->getUserEligibility($user)){
                return [
                    'status'=>'failed',
                    'message'=>'Coupon Has Been Expired'
                ];
            }

            $coupon_discount=$order->getCouponDiscount($coupon);
            if($coupon_discount)
                $order->applyCoupon($coupon);
        }

        if($order->use_balance==1) {
            $result=$this->payUsingBalance($order);
            if($result['status']=='success'){

                event(new OrderConfirmed($order));

                return [
                    'status'=>'success',
                    'message'=>'Congratulations! Your order at Hallobasket is successful',
                    'data'=>[
                        'payment_done'=>'yes',
                        'ref_id'=>$order->refid,
                        'refid'=>$order->refid,
                        'order_id'=>$order->id
                    ]
                ];
            }
        }

        if($order->use_points==1) {
            $result=$this->payUsingPoints($order);
            if($result['status']=='success'){

                event(new OrderConfirmed($order));

                return [
                    'status'=>'success',
                    'message'=>'Congratulations! Your order at Hallobasket is successful',
                    'data'=>[
                        'payment_done'=>'yes',
                        'ref_id'=>$order->refid,
                        'refid'=>$order->refid,
                        'order_id'=>$order->id
                    ]
                ];
            }
        }

        if($order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->balance_used-$order->points_used == 0){
            $order->payment_status='paid';
            $order->status='confirmed';
            $order->payment_mode='online';
            $order->save();

            if($order->points_used > 0)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->points_used, 'POINT', $order->id);

            if($order->balance_used > 0)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

            Cart::deleteUserCart($user->id);

            //event(new OrderConfirmed($order));

            return [
                'status'=>'success',
                'message'=>'Congratulations! Your order at Hallobasket is successful',
                'data'=>[
                    'payment_done'=>'yes',
                    'ref_id'=>$order->refid,
                    'order_id'=>$order->id
                ]
            ];

        }else{
            if($request->type=='cod'){
                return $this->initiateCODPayment($order);
            }else{
                return $this->initiateGatewayPayment($order);
            }
        }

    }

    private function initiateGatewayPayment($order){
        $data=[
            "amount"=>($order->total_cost+$order->delivery_charge+$order->extra_amount-$order->coupon_discount-$order->points_used-$order->balance_used)*100,
            "currency"=>"INR",
            "receipt"=>$order->refid,
        ];

        $response=$this->pay->generateorderid($data);

        LogData::create([
            'data'=>($response.' orderid:'.$order->id. ' '.json_encode($data)),
            'type'=>'order'
        ]);

        $responsearr=json_decode($response);
        //var_dump($responsearr);die;
        if(isset($responsearr->id)){
            $order->order_id=$responsearr->id;
            $order->order_id_response=$response;
            $order->save();
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>[
                    'payment_done'=>'no',
                    'razorpay_order_id'=> $order->order_id,
                    'total'=>($order->total_cost+$order->delivery_charge+$order->extra_amount-$order->coupon_discount-$order->points_used-$order->balance_used)*100,
                    'email'=>$order->email,
                    'mobile'=>$order->mobile,
                    'description'=>'Product Purchase at HalloBasket',
                    'name'=>$order->name,
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

    private function initiateCodPayment($order){
        $user=auth()->guard('customerapi')->user();
        if($user->status==2){
            return [
                'status'=>'failed',
                'message'=>'Your Account Has Been Blocked'
            ];
        }

        $order->payment_mode='COD';
        $order->status='confirmed';
        $order->save();

        if($order->points_used > 0)
            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->points_used, 'POINT', $order->id);

        if($order->balance_used > 0)
            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

        event(new OrderConfirmed($order));

        Cart::deleteUserCart($user->id);

        return [
            'status'=>'success',
            'message'=>'Congratulations! Your order at HalloBasket is successful',
            'data'=>[
                'payment_done'=>'yes',
                'refid'=>$order->refid,
                'order_id'=>$order->id
            ],
        ];
    }

    public function verifyPayment(Request $request){

        $request->validate([
            'razorpay_order_id'=>'required',
            'razorpay_signature'=>'required',
            'razorpay_payment_id'=>'required'

        ]);


        LogData::create([
            'data'=>(json_encode($request->all())??'No Payment Verify Data Found'),
            'type'=>'verify'
        ]);

        $order=Order::with('details')->where('order_id', $request->razorpay_order_id)->first();

        if(!$order || $order->status!='pending')
            return [
                'status'=>'failed',
                'message'=>'Invalid Operation Performed'
            ];

        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult) {
            if ($order->use_points == true) {
                $walletpoints = Wallet::points($order->user_id);
                if ($walletpoints < $order->points_used) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'We apologize, Your order is not successful due to low cashback balance',
                        'errors' => [

                        ],
                    ], 200);
                }
            }

            if ($order->use_balance == true) {
                $balance = Wallet::balance($order->user_id);
                if ($balance < $order->balance_used) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'We apologize, Your order is not successful due to low wallet balance',
                        'errors' => [

                        ],
                    ], 200);
                }
            }
            $order->status = 'confirmed';
            $order->payment_id = $request->razorpay_payment_id;
            $order->payment_id_response = $request->razorpay_signature;
            $order->payment_status = 'paid';
            $order->payment_mode = 'online';
            $order->save();


            OrderStatus::create([
                'order_id'=>$order->id,
                'current_status'=>$order->status
            ]);

            if($order->points_used > 0)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->points_used, 'POINT', $order->id);

            if($order->balance_used > 0)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

            Cart::deleteUserCart($order->user_id);
            //event(new OrderSuccessfull($order));
            event(new OrderConfirmed($order));
            return [
                'status'=>'success',
                'message'=> 'Congratulations! Your order at Hallobasket is successful',
                'data'=>[
                    'ref_id'=>$order->refid,
                    'order_id'=>$order->id,
                    'refid'=>$order->refid,
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
