<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\NotificationToken;
use App\Models\Order;
use App\Services\Notification\FCMNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title,$message,$stores,$imagepath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $message, $imagepath, $stores)
    {
        $this->title=$title;
        $this->message=$message;
        $this->stores=$stores;
        $this->imagepath=$imagepath;
        //die('xcdf');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //var_dump($this->stores);die;

        $tokens=NotificationToken::with('user')->get();
        $all_sent=false;
        $user_sent=[];
        //var_dump($tokens->toArray());die;
        foreach($tokens as $token){

            if($token->user_id){
                $message=str_replace('{{name}}', $token->user->name??'User', $this->message);
                $message=str_replace('{{Name}}', $token->user->name??'User', $message);
                if(!in_array($token->user_id, $user_sent)){
                    Notification::create([
                        'user_id'=>$token->user_id,
                        'title'=>$this->title,
                        'image'=>$this->imagepath,
                        'description'=>$message,
                        'type'=>'individual'
                    ]);

                    $user_sent[]=$token->user_id;

                }
            }else{
                $message=str_replace('{{name}}', $token->user->name??'User', $this->message);
                $message=str_replace('{{Name}}', $token->user->name??'User', $message);
                if($all_sent==false){
                    Notification::create([
                        'title'=>$this->title,
                        'description'=>$message,
                        'image'=>$this->imagepath,
                        'type'=>'all'
                    ]);
                    $all_sent=true;
                }

            }
            if($this->imagepath)
                $image_path=Storage::url($this->imagepath);
            else
                $image_path=null;

            $token->notify(new FCMNotification($this->title, $message, ['image'=>$image_path, 'title'=>$this->title, 'body'=>$message], 'notification_screen'));

        }



//        if($this->stores)
//            $users=Order::whereIn('store_id', $this->stores)->select('user_id')->get();
//        else
//            $users=Order::select('user_id')->get();
//
//        $user_ids=$users->map(function($id){
//            return $id->user_id;
//        });
//
//        if(!empty($user_ids)){
//            $tokens=Customer::whereIn('id', $user_ids)
//                ->where('notification_token', '!=', null)
//                ->select('notification_token', 'id')
//                ->get();
//        }else{
//            $tokens=Customer::
//            where('notification_token', '!=', null)
//                ->select('notification_token', 'id')
//                ->get();
//        }
//        $tokens_arr=[];
//        foreach($tokens as $token){
//
//            $message=str_replace('{{name}}', $token->name??'User', $this->message);
//            $message=str_replace('{{Name}}', $token->name??'User', $message);
//
//            Notification::create([
//                'user_id'=>$token->id,
//                'title'=>$this->title,
//                'description'=>$message,
//                'data'=>null,
//                'type'=>'individual'
//            ]);
//
//            if(in_array($token->notification_token, $tokens_arr))
//                continue;
//
//            FCMNotification::sendNotification($token->notification_token, $this->title, $message);
//
//            $tokens_arr[]=$token->notification_token;
//
//        }
    }
}
