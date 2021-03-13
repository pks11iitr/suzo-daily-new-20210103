<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeSection extends Model
{
    use Active,DocumentUploadTrait;
    protected $table='home_sections';

    protected $fillable=['sequence_no','name','image','type','isactive','title'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function entities(){
        return $this->hasMany('App\Models\HomeSectionEntity', 'home_section_id');
    }

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
}
