<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table='order_details';

    protected $fillable = ['order_id', 'user_id', 'product_id', 'quantity','type','start_date','time_slot_id','no_of_days', 'total_quantity', 'scheduled_quantity', 'delivered_quantity', 'status'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function days(){
        return $this->belongsToMany('App\Models\BookDay', 'subscription_days', 'item_id','day');
    }

    public function timeslot(){
        return $this->belongsToMany('App\Models\TimeSlot', 'time_slot_id');
    }

}
