<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class Order extends Model
{
    use HasFactory;

    protected $table='orders';

    protected $fillable=['refid', 'total_cost', 'status', 'payment_status', 'payment_mode', 'order_details_completed', 'booking_date', 'booking_time', 'user_id', 'name', 'email', 'mobile', 'address_id', 'lat', 'lang', 'is_instant', 'use_wallet', 'use_points', 'balance_used', 'points_used','schedule_type','order_place_state','coupon_applied', 'coupon_discount', 'delivery_charge', 'delivery_date', 'delivery_slot', 'delivered_at', 'cashback_given','store_id','is_express_delivery','cancel_reason','savings'];


    public function details(){
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function deliveryaddress(){
        return $this->belongsTo('App\Models\CustomerAddress', 'address_id');
    }

    public function rider(){
        return $this->belongsTo('App\Models\Rider', 'rider_id');
    }

    public function store(){
        return $this->belongsTo('App\Models\User', 'store_id');
    }

    public function applyCoupon($coupon){
        $discount=$this->getCouponDiscount($coupon);
        $this->coupon=$coupon->code;
        $this->coupon_discount=$discount;
        $this->save();
    }

    public function getCouponDiscount($coupon){
        $eligible_amount=$this->getDiscountEligibleAmount($coupon);
        $discount=$coupon->getCouponDiscount($eligible_amount);
        return $discount;
    }

    public function getDiscountEligibleAmount($coupon){
        $amount=0;
        $coupon_cat=$coupon->categories->map(function($element){
            return $element->id;
        });
        $coupon_cat=$coupon_cat->toArray();
        foreach($this->details as $detail){
            if(count($coupon_cat)){
                $product_cat=$detail->product->subcategory->map(function($element){
                    return $element->id;
                });
                $product_cat=$product_cat->toArray();
                if(count(array_intersect($product_cat,$coupon_cat))){
                    if($detail->type=='subscription'){
                        $amount=$amount+$detail->price*$detail->total_quantity;
                    }else{
                        $amount=$amount+$detail->price*$detail->quantity;
                    }
                }
            }
        }

        if($amount>0)
            return $amount;

        $coupon_cat=$coupon->specialcategories->map(function($element){
            return $element->id;
        });
        $coupon_cat=$coupon_cat->toArray();
        foreach($this->details as $detail){
            if(count($coupon_cat)){
                $product_cat=$detail->product->specialcategory->map(function($element){
                    return $element->id;
                });
                $product_cat=$product_cat->toArray();
                if(count(array_intersect($product_cat,$coupon_cat))){
                    if($detail->type=='subscription'){
                        $amount=$amount+$detail->price*$detail->total_quantity;
                    }else{
                        $amount=$amount+$detail->price*$detail->quantity;
                    }
                }
            }
        }
        return $amount;

    }

    public function getMembershipEligibleDiscount($membership){
        $amount=0;
        $membership_cat=$membership->categories->map(function($element){
            return $element->id;
        });
        $membership_cat=$membership_cat->toArray();
        foreach($this->details as $detail){
            if(count($membership_cat)){
                $product_cat=$detail->product->subcategory->map(function($element){
                    return $element->id;
                });
                $product_cat=$product_cat->toArray();
                if(count(array_intersect($product_cat,$membership_cat))){
                    if($detail->type=='subscription'){
                        $amount=$amount+$detail->price*$detail->total_quantity;
                    }else{
                        $amount=$amount+$detail->price*$detail->quantity;
                    }
                }
            }else{
                if($detail->type=='subscription'){
                    $amount=$amount+$detail->price*$detail->total_quantity;
                }else{
                    $amount=$amount+$detail->price*$detail->quantity;
                }
            }
        }
        return $amount;
    }


    public function returned(){
        return $this->hasMany('App\Models\ReturnProduct', 'order_id');
    }

}
