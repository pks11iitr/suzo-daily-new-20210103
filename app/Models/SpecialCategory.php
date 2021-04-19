<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class SpecialCategory extends Model
{
    use HasFactory;

    protected $table='special_category';

    protected $fillable =['name', 'isactive'];

    public function products(){
        return $this->belongsToMany('App\Models\Product', 'special_category_product', 'category_id', 'product_id');
    }

}
