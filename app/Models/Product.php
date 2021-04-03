<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use App\Models\Traits\ReviewTrait;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use ReviewTrait, Active;

	protected $table='products';
    protected $fillable=['name','description','company','ratings','image','isactive','stock_type','image','is_offer','min_qty','max_qty','stock','description','is_discounted','is_newarrival','is_hotdeal','price','cut_price', 'cgst', 'sgst', 'eligible_goldcash', 'can_be_subscribed', 'subscription_cashback', 'delivery_charge'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
    protected $appends=['discount'];
    public function getDiscountAttribute($value){
        return empty($this->cut_price)?0:intval((($this->cut_price-$this->price)/$this->cut_price)*100);
    }
		public function subcategory(){
        return $this->belongsToMany('App\Models\SubCategory', 'product_category', 'product_id', 'sub_cat_id');
    }
		public function category(){
        return $this->belongsToMany('App\Models\Category', 'product_category', 'product_id', 'category_id');
    }
    public function specialcategory(){
        return $this->belongsToMany('App\Models\SpecialCategory', 'special_category_product', 'product_id','category_id');
    }

}
