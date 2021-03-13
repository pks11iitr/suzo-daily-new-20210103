<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use Active,HasFactory;
    protected $table='banners';
    protected $fillable=['image','isactive','type', 'entity_type', 'entity_id', 'parent_id'];

    protected $hidden = ['created_at','deleted_at','updated_at'];
    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }

    public function entity(){
        return $this->morphTo();
    }
}
