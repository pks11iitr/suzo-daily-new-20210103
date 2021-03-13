<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;
    protected $table='documents';
    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
}
