<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table='order_details';

    protected $fillable = ['order_id', 'user_id', 'product_id', 'quantity','type','start_date','time_slot_id','no_of_days', 'total_quantity', 'scheduled_quantity', 'delivered_quantity', 'status', 'last_delivery_at', 'remark', 'price','cut_price'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function days(){
        return $this->belongsToMany('App\Models\BookDay', 'subscription_days', 'item_id','day');
    }

    public function timeslot(){
        return $this->belongsTo('App\Models\TimeSlot', 'time_slot_id');
    }

    public function deliveries(){
        return $this->hasMany('App\Models\DailyDelivery', 'detail_id');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

}
