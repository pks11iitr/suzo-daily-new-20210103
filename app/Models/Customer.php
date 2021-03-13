<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DateTime;
class Customer extends Authenticatable implements JWTSubject
{
    use DocumentUploadTrait;

    protected $table='customers';

    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'image', 'dob', 'address','country_id', 'city_id', 'state_id','pincode', 'status','notification_token', 'gender', 'education_id', 'occupation_id', 'employement_id', 'salaray_id', 'religion_id', 'height_id', 'language_id', 'marital_status_id', 'salary_id', 'about_me'
    ];

    protected $hidden = [
        'password','created_at','deleted_at','updated_at','email','mobile'
    ];

    protected $appends=['age'];

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

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function state(){
        return $this->belongsTo('App\Models\State', 'state_id');
    }


    public function religion(){
        return $this->belongsTo('App\Models\City', 'religion_id');
    }

    public function salary(){
        return $this->belongsTo('App\Models\Income', 'salary_id');
    }

    public function height(){
        return $this->belongsTo('App\Models\Height', 'height_id');
    }

    public function getAgeAttribute($value){
        if($this->dob)
            return $this->getAgeDifference($this->dob);
        return '--';
    }

    function getAgeDifference($date){

        $text='--';

        if($date){
            $date1 = new DateTime(date('Y-m-d H:i:s'));
            $date2 = $date1->diff(new DateTime($date));

            $text='';

            if($date2->y)
                $text=$text.$date2->y.' year'.' ';

            if($date2->m)
                $text=$text.$date2->m.' month';
        }

        return $text;
    }
}
