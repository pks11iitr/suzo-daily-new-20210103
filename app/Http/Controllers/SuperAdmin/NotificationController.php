<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Jobs\SendBulkNotifications;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function create(Request $request){
        $stores=User::where('id', '>', 1)->select('name', 'id')->get();
        return view('admin.notification.add', compact('stores'));
               }

   public function store(Request $request){
//               $request->validate([
//                  			'title'=>'required',
//                  			'description'=>'required'
//                               ]);
             //die('sds');
             if(isset($request->image)){
                 $name = $request->image->getClientOriginalName();
                 $contents = file_get_contents($request->image);
                 $path = 'notifications/'. rand(111, 999) . '_' . str_replace(' ','_', $name);
                 \Storage::put($path, $contents, 'public');
             }

             dispatch(new SendBulkNotifications($request->title,$request->description,$path??null, $request->type));
             return redirect()->back()->with('success', 'Notification Send Successfully');

          }

  }
