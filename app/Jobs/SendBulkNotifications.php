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

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title,$message,$stores;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $message, $stores)
    {
        $this->title=$title;
        $this->message=$message;
        $this->stores=$stores;
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
        //var_dump($tokens->toArray());die;
        foreach($tokens as $t){

            if($t->user_id){
                $message=str_replace('{{name}}', $token->user->name??'User', $this->message);
                $message=str_replace('{{Name}}', $token->user->name??'User', $message);

                Notification::create([
                    'user_id'=>$token->user_id,
                    'title'=>$this->title,
                    'description'=>$message,
                    'type'=>'individual'
                ]);
            }else{
                $message=str_replace('{{name}}', $token->user->name??'User', $this->message);
                $message=str_replace('{{Name}}', $token->user->name??'User', $message);
                if($all_sent==false){
                    Notification::create([
                        'title'=>$this->title,
                        'description'=>$message,
                        'type'=>'all'
                    ]);
                    $all_sent=true;
                }

            }

            $t->notify(new FCMNotification($this->title, $message, []));

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
