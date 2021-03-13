<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class CategoryProduct extends Model
{
    use HasFactory;
    protected $table='product_category';
    protected $fillable=['category_id','product_id','sub_cat_id'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function subcategory(){
        return $this->belongsTo('App\Models\SubCategory', 'sub_cat_id');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }


}
