<?php

namespace App\Http\Controllers\MobileApps\Auth;

use App\Models\Customer;
use App\Models\OTPModel;
use App\Services\SMS\Msg91;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){

        $customer=$this->getCustomer($request);
        if(!$customer){
            return [
                'status'=>'failed',
                'message'=>'This account is not registered with us'
            ];
        }
        $otp=OTPModel::createOTP('customer', $customer->id, 'reset');
        $msg=str_replace('{{otp}}', $otp, config('sms-templates.reset'));
        Msg91::send($customer->mobile,$msg);
        return ['status'=>'success', 'message'=>'otp verify', 'token'=>''];
    }


    protected function getCustomer(Request $request){
        $customer=Customer::where($this->userId($request),$request->user_id)->first();
//        $customer->notification_token=$request->notification_token;
//        $customer->save();
        return $customer;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function userId(Request $request, $type='password')
    {
        if(filter_var($request->user_id, FILTER_VALIDATE_EMAIL))
            return 'email';
        else
            return 'mobile';
    }

    public function updatePassword(Request $request){

        $user=auth()->guard('customerapi')->user();
        if(!$user){
            return [
                'status'=>'failed',
                'message'=>'Invalid Request'
            ];
        }

        $user->password=Hash::make($request->password);
        $user->save();

        return [
            'status'=>'success',
            'message'=>'Password Has Been Updated Successfully. Please log in to continue.'
        ];

    }

}
