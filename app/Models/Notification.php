<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Notification extends Model
{
    use HasFactory;


    protected $table='notifications';

    protected $fillable=['title', 'description', 'type', 'user_id', 'image'];


    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return '';
    }

    public function getCreatedAtAttribute($value){
        if($value)
            return date('d M', strtotime($value));
        return '';
    }
}
