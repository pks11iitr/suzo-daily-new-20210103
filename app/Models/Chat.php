<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class Chat extends Model
{
    use HasFactory;

    protected $table='chats';

    protected $fillable=['user_1', 'user_2', 'direction', 'message'];


    public function user1(){
        return $this->belongsTo('App\Models\Customer', 'user_1');
    }

    public function user2(){
        return $this->belongsTo('App\Models\Customer', 'user_2');
    }
}
