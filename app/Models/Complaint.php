<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table='complaints';

	protected $fillable=['user_id', 'subject', 'refid', 'category'];

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function messsages()
    {
        return $this->hasMany('App\Models\ComplainMessage', 'complaint_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\ComplaintMessage', 'complaint_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }
}
