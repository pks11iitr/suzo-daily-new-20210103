<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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

    public function index(Request $request){
        $user=auth()->guard('customerapi')->user();

        if($user){
            $notifications=Notification::where(function($query) use($user){
                $query->where('user_id', $user->id)->where('type','individual');
            })->orWhere('type', 'all')
                ->orderBy('id', 'desc')
                ->select('id', 'title', 'description', 'image')
                ->take(20)
                ->get();
        }
        else{
            $notifications=Notification::where('type', 'all')
                ->orderBy('id', 'desc')
                ->select('id', 'title', 'description', 'image')
                ->take(20)
                ->get();
        }

        return [
            'status'=>'success',
            'data'=>compact('notifications')
        ];
    }
}
