<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use Active, DocumentUploadTrait;
    use HasFactory;
    protected $table='categories';
    protected $fillable=['name','image','isactive'];

    protected $hidden = ['created_at','deleted_at','updated_at'];
    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }


    public function subcategories(){
        return $this->hasMany('App\Models\SubCategory', 'category_id');
    }
}
