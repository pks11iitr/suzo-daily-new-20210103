<?php

namespace App\Models;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;

class NotificationToken extends Model
{
    use HasFactory, Notifiable;

    protected $table='tokens';

    protected $fillable=['user_id', 'notification_token'];

    public function routeNotificationForFcm()
    {
        $arr=[];
        $tokens=$this->tokens;
        foreach($tokens as $t)
            $arr[]=$t->notification_token;
        return $arr;
    }

    public function notify($instance)
    {
        try{
            app(Dispatcher::class)->send($this, $instance);

        }catch(CouldNotSendNotification $e){

        }

    }


    public function user(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }
}
