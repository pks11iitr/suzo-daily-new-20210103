<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table='messages';

    protected $fillable =['sender', 'receiver', 'type', 'message', 'status'];


    public function senderUser(){
        return $this->belongsTo('App\Models\Customer', 'sender');
    }

    public function receiverUser(){
        return $this->belongsTo('App\Models\Customer', 'receiver');
    }
}
