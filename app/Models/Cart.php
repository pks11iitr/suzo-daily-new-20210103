<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class Cart extends Model
{
    protected $table= 'cart';

    protected $fillable = ['user_id', 'product_id', 'quantity','size_id','type','start_date','time_slot_id','no_of_days', 'total_quantity'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
   /* public function sizeprice(){
        return $this->belongsTo('App\Models\Size', 'size_id');
    }*/
    public function images(){
        return $this->belongsTo('App\Models\ProductImage', 'size_id');
    }
    public function days(){
        return $this->belongsToMany('App\Models\BookDay', 'daysslots', 'cart_id','day');
    }

    public function timeslot(){
        return $this->belongsTo('App\Models\TimeSlot', 'time_slot_id');
    }


    public static function getUserCart($user){
        if(!$user)
            return [
                'cart'=>[],
                'total'=>0,
                'price_total'=>0
            ];
        $cart=[];
        $items=Cart::with(['product', 'days'])
            ->where('user_id', $user->id)
            ->get();

        $total=0;
        $price_total=0;
        foreach ($items as $item){

            $cart[$item->product_id]['cart_quantity']=$item->quantity;
            $cart[$item->product_id]['cart_type']=$item->type;
            //$cart[$item->product_id]['in_stock']=$item->product->stock??0;
            if($item->type=='subscription'){
                $price_total=$price_total+$item->quantity*($item->product->price??0)*$item->no_of_days;
                $total=$total+$item->quantity*$item->no_of_days;
            }
            else{
                $price_total=$price_total+$item->quantity*$item->no_of_days;
                $total=$total+$item->quantity;
            }
        }

        $price_total=round($price_total, 2);

        return compact('cart', 'total', 'price_total');
    }

   /* public static function removeOutOfStockItems($item){
        //foreach ($items as $item){
            if((!$item->product->isactive) || (!$item->sizeprice->isactive)){
                $item->delete();
                return true;
            }

            if($item->product->stock_type=='quantity'){
                if($item->product->stock < $item->quantity){
                    $item->delete();
                    return true;
                }
            }else{
                if($item->sizeprice->stock < $item->quantity){
                    $item->delete();
                    return true;
                }
            }
            if($item->quantity < $item->sizeprice->min_qty || $item->quantity > $item->sizeprice->max_qty){
                $item->delete();
                return true;
            }

            return false;
        //}
    }*/
    public static function updateManyItemTimeSlot($items, $next_available_slot){
        $invalid_time_items_id=[];
        foreach($items as $item){
            if(!$item->timeslot->checkTimings($item->start_date)){
                $invalid_time_items_id[]=$item->id;
            }
        }
        if(count($invalid_time_items_id)){
            Cart::whereIn('id', $invalid_time_items_id)
                ->update(['start_date'=>$next_available_slot['date'], 'time_slot_id'=>$next_available_slot['id']]);
        }
    }


    public static function updateSingleItemTimeSlot($item, $next_available_slot){
        //var_dump($item->timeslot->checkTimings($item->start_date));die;
            if(!$item->timeslot->checkTimings($item->start_date)){
                //echo 'die';die;
                $item->time_slot_id=$next_available_slot['id'];
                $item->start_date=$next_available_slot['date'];
                $item->save();
            }
    }


    public static function deleteUserCart($user_id){
        $cart=Cart::where('user_id', $user_id)
            ->get();
        if($cart){
            foreach($cart as $c)
                if($c->type=='subscription'){
                    $c->days()->sync([]);
                }
        }
        Cart::where('user_id', $user_id)
            ->delete();
    }


    public static function getCouponDiscount($cart, $coupon){
        $eligible_amount=self::getDiscountEligibleAmount($cart, $coupon);
        $discount=$coupon->getCouponDiscount($eligible_amount);
        return $discount;
    }

    public static function getDiscountEligibleAmount($cart, $coupon){
        $amount=0;
        $coupon_cat=$coupon->categories->map(function($element){
            return $element->id;
        });
        $coupon_cat=$coupon_cat->toArray();
        foreach($cart as $detail){
            if(count($coupon_cat)){
                $product_cat=$detail->product->subcategory->map(function($element){
                    return $element->id;
                });
                $product_cat=$product_cat->toArray();
                if(count(array_intersect($product_cat,$coupon_cat))){
                    if($detail->type=='subscription'){
                        $amount=$amount+$detail->product->price*$detail->quantity*$detail->no_of_days;
                    }else{
                        $amount=$amount+$detail->product->price*$detail->quantity;
                    }
                }
            }
        }
        return $amount;
    }

    public static function getEligibleGoldCash($cart){
        $eligible=0;
        foreach($cart as $c){

            if($c->product->eligible_goldcash){
                $eligible=$c->price*$c->product->eligible_goldcash/100;
            }

        }

        return round($eligible, 2);

    }


}
