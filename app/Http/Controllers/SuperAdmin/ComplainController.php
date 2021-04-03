<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class ComplainController extends Controller
{
     public function index(Request $request){
            $complaints=Complaint::orderBy('updated_at','DESC')
                ->paginate(10);
            return view('admin.complain.view',['complaints'=>$complaints]);
              }

    public function details(Request $request,$id){
        $complaint=Complaint::findOrFail($id);
            $complaints=ComplaintMessage::orderBy('id','ASC')->where('complaint_id','=',$id)->get();
            return view('admin.complain.message',['complaints'=>$complaints,'compid'=>$id, 'complaint'=>$complaint]);
              }

     public function send_message(Request $request)
        {

        $compid=$request->compid;
        $msg=$request->des;
        $message=ComplaintMessage::create([
                      'complaint_id'=>$compid,
                      'description'=>$msg,
                      'type'=>'admin'
                      ]);
         if($message){
           return response()->json(['users' => $message], 200);
       }else{
              return response()->json(['msg' => 'No result found!'], 404);
       }

        }


     public function markAsClosed(Request $request, $id){

         $complaint=Complaint::findOrFail($id);

         $complaint->is_closed=true;
         $complaint->save();

         return redirect()->back()->with('success', 'Complaint has been marked as closed');

     }

  }
