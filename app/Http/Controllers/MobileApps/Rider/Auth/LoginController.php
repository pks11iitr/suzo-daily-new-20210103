<?php

namespace App\Http\Controllers\MobileApps\Rider\Auth;

use App\Events\SendOtp;
use App\Models\Customer;
use App\Models\OTPModel;
use App\Models\Rider;
use App\Services\SMS\Msg91;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

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

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'user_id' => $this->userId($request)=='email'?'required|email|string|exists:riders,email':'required|digits:10|string|exists:riders,mobile',
            'password' => 'required|string',
        ], ['user_id.exists'=>'This account is not registered with us. Please signup to continue']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($token=$this->attemptLogin($request)) {
            return $this->sendLoginResponse($this->getCustomer($request), $token);
        }
        return [
            'status'=>'failed',
            'token'=>'',
            'message'=>'Credentials are not correct'
        ];

    }


    protected function attemptLogin(Request $request)
    {
        return Auth::guard('riderapi')->attempt(
            [$this->userId($request)=>$request->user_id, 'password'=>$request->password]
        );
    }

    protected function getCustomer(Request $request){
        return Rider::where($this->userId($request),$request->user_id)->first();
    }

    protected function sendLoginResponse($user, $token){
        if($user->isactive==1){
            $otp=OTPModel::createOTP('riders', $user->id, 'login');
            $msg=str_replace('{{otp}}', $otp, config('sms-templates.login'));
            Msg91::send($user->mobile,$msg);
            return ['status'=>'success', 'message'=>'otp verify', 'token'=>''];
        }
        else
            return ['status'=>'failed', 'message'=>'This account has been blocked', 'token'=>''];
    }

}
