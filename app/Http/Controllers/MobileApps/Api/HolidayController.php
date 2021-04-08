<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function get(Request $request){
        $user=$request->user;

        if($user->end_date>=date('Y-m-d')){

            $type='yes';
            $start_date=$user->holiday_start;
            $end_date=$user->holiday_end;

            return [
                'status'=>'success',
                'data'=>compact('type', 'start_date', 'end_date')
            ];
        }

        $type='no';
        $start_date='';
        $end_date='';

        return [
            'status'=>'success',
            'data'=>compact('type', 'start_date', 'end_date')
        ];


    }


    public function set(Request $request){
        $user=$request->user;

        if($request->type=='no'){
            $user->holiday_start=null;
            $user->holiday_end=null;
            $user->save();

            return [
                'status'=>'success',
                'message'=>'Preferences have been updated'
            ];

        }

        $request->validate([
            'type'=>'required|in:yes,no',
            'start_date'=>'required|date_format:Y-m-d',
            'end_date'=>'required|date_format:Y-m-d'
        ]);

        if($request->holiday_start < $request->holiday_end){
            return [
                'status'=>'Failed',
                'message'=>'End date must be greater than Start date'
            ];
        }
        $user->holiday_start=$request->start_date;
        $user->holiday_end=$request->end_date;
        $user->save();

        return [
            'status'=>'success',
            'message'=>'Preferences have been updated'
        ];

    }
}
