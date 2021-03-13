<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasFactory,Active;

    protected $table='stories';

    protected $hidden = ['deleted_at','updated_at'];

    protected $fillable = ['image', 'description', 'isactive','title'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return '';
    }
}
