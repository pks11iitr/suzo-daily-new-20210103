<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use DateTime;
class Customer extends Authenticatable implements JWTSubject, Auditable
{
    use DocumentUploadTrait, Notifiable, \OwenIt\Auditing\Auditable;

    protected $table='customers';

    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'image', 'dob', 'address','country_id', 'city_id', 'state_id','pincode', 'status','notification_token', 'gender', 'education_id', 'occupation_id', 'employement_id', 'salaray_id', 'religion_id', 'height_id', 'language_id', 'marital_status_id', 'salary_id', 'about_me'
    ];

    protected $hidden = [
        'password','created_at','deleted_at','updated_at','email','mobile'
    ];

    //protected $appends=['age'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        $arr=[];
        $tokens=$this->tokens;
        foreach($tokens as $t)
            $arr[]=$t->notification_token;
        return $arr;
    }

    public function tokens(){
        return $this->hasMany('App\Models\NotificationToken', 'user_id');
    }

    public function notify($instance)
    {
        try{
            app(Dispatcher::class)->send($this, $instance);

        }catch(CouldNotSendNotification $e){

        }

    }

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function isMembershipActive(){
        if($this->membership_expiry>date('Y-m-d'))
            return true;
        return false;

    }

    public function membership(){
        return $this->belongsTo('App\Models\Membership', 'membership_id');
    }
}
