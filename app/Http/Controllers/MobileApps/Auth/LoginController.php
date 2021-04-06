<?php

namespace App\Http\Controllers\MobileApps\Auth;

use App\Events\SendOtp;
use App\Models\Customer;
use App\Models\OTPModel;
use App\Services\SMS\Msg91;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    protected function validateOTPLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10|string',
        ]);
    }

    public function loginWithOtp(Request $request){
        $this->validateOTPLogin($request);

        $user=Customer::where('mobile', $request->mobile)->first();
        if(!$user) {
            $user = $this->create($request->all());
            $otp = OTPModel::createOTP('customer', $user->id, 'login');
            $msg = str_replace('{{otp}}', $otp, config('sms-templates.login'));
            event(new SendOtp($user->mobile, $msg));
            return ['status' => 'success', 'message' => 'Please verify OTP to continue'];
        }else {

            if (!in_array($user->status, [0, 1]))
                return ['status' => 'failed', 'message' => 'This account has been blocked'];

            $otp = OTPModel::createOTP('customer', $user->id, 'login');
            $msg = str_replace('{{otp}}', $otp, config('sms-templates.login'));
            event(new SendOtp($user->mobile, $msg));

            return ['status' => 'success', 'message' => 'Please verify OTP to continue'];
        }
    }


    protected function create(array $data)
    {
        return Customer::create([
            'password' => Hash::make($data['mobile']),
            'mobile'=>$data['mobile'],
        ]);
    }


}
