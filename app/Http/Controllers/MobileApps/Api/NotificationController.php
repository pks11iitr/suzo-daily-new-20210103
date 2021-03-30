<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationToken;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function storeToken(Request $request){
        if(!NotificationToken::where('notification_token', $request->notification_token)->first())
        NotificationToken::create([
            'notification_token'=>$request->notification_token
        ]);
        return [
            'status'=>'success'
        ];
    }
}
