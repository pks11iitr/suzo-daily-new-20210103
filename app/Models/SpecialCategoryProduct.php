<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCategoryProduct extends Model
{
    use HasFactory;

    protected $table='special_category_product';

    protected $fillable =['category_id','product_id'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

}
