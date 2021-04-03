<?php

namespace App\Http\Controllers\MobileApps\Rider\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{

    public function attendences(Request $request){
        $user = $request->user;
        $attendences=Attendence::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return [
            'status'=>'success',
            'data'=>compact('attendences')
        ];
    }

    public function checkin(Request $request){

        $user=$request->user;

        $request->validate([
            'lat'=>'required',
            'lang'=>'required',
            'map_address'=>'required',
            'image'=>'required',
        ]);


        $attendence=Attendence::create([
            'user_id'=>$user->id,
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'map_address'=>$request->address,
        ]);

        if($request->image)
            $attendence->saveImage($request->image, 'rider');

        return [
            'status'=>'success',
            'message'=>'Attendence has been submitted'
        ];

    }
}
