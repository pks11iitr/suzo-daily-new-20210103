<?php

namespace App\Http\Controllers\MobileApps\Auth;

use App\Events\CustomerRegistered;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'mobile'=>['required', 'string', 'max:10']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return Customer::create([
            'name' => $data['name'],
            'mobile'=>$data['mobile'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if($customer=Customer::where('mobile', $request->mobile)->orWhere('email', $request->email)->first()){
            return [
                'status'=>'failed',
                'message'=>'Email or mobile already registered'
            ];
        }
        $user = $this->create($request->all());
        event(new CustomerRegistered($user));

        return [
            'status'=>'success',
            'message'=>'Please verify otp to continue'
        ];
    }
}
