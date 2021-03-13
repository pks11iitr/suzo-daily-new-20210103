<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class SubCategory extends Model
{
    use Active;
    use HasFactory;
    protected $table='sub_category';

    protected $fillable=['name','category_id','isactive'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
