<?php

namespace App\Http\Controllers\Customer\Api;

use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComplaintController extends Controller
{
    public function index(Request $request){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $complaints=Complaint::where('user_id', $user->id)->select('id','refid','subject','created_at')->get();

        return [
            'status'=>'success',
            'data'=>compact('complaints'),
        ];

    }

    public function create(Request $request){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $request->validate([
            'category'=>'required|max:150',
            'subject'=>'required|max:200',
            'description'=>'required|max:1500'
        ]);


        if($complaint=Complaint::create([
            'user_id'=>$user->id,
            'subject'=>$request->subject,
            'category'=>$request->category,
            'refid'=>'C'.env('MACHINE_ID').time()
        ])){

            $message=new ComplaintMessage(
                [
                    'description'=>$request->description,
                    'user_id'=>$user->id,
                    'type'=>'user'
                ]);
            $complaint->messages()->save($message);

            return [
                'status'=>'success',
                'message'=>'Your Complaint Has Been Submitted',
                'data'=>[
                    'complaint_id'=>$complaint->id
                ]

            ];
        }

    }

    public function messages(Request $request, $complaint_id){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $complaint=Complaint::with(['messages', 'user'])->where('user_id', $user->id)->find($complaint_id);
        if(!$complaint)
            return [
                'status'=>'failed',
                'message'=>'No such complaint found'
            ];

        $messages=$complaint->messages()->orderBy('id', 'asc')->get();
        $message_list=[];
        foreach($messages as $m){
            $message_list[]=[
                'description'=>$m->description,
                'orientation'=>$m->type=='user'?'left':'right',
                'image'=>$m->type=='user'?$complaint->user->image:''
            ];
        }


        return [
            'status'=>'success',
            'data'=>[
                'messages'=>$message_list
            ]
        ];
    }

    public function postMessage(Request $request, $complaint_id){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $complaint=Complaint::where('user_id', $user->id)->find($complaint_id);
        if(!$complaint)
            return [
                'status'=>'failed',
                'message'=>'No such complaint found'
            ];

        $complaint->updated_at=date('Y-m-d H:i:s');
        $complaint->is_closed=false;
        $complaint->save();

        $message=new ComplaintMessage(
            [
                'description'=>$request->message,
                'user_id'=>$user->id,
                'type'=>'user'
            ]);
        $complaint->messages()->save($message);

        return [
            'status'=>'success',
            'message'=>'Message submitted successfully'
        ];
    }

}
