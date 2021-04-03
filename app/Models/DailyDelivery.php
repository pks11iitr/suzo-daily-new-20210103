<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class DailyDelivery extends Model
{
    use HasFactory;

    protected $table='daily_deliveries';

    protected $fillable =['order_id', 'detail_id', 'user_id', 'product_id', 'quantity', 'delivery_date', 'delivery_time_slot', 'address_id', 'rider_id', 'store_id', 'area_id', 'notification_status'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function detail(){
        return $this->belongsTo('App\Models\OrderDetail', 'detail_id');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function rider(){
        return $this->belongsTo('App\Models\Rider', 'rider_id');
    }

    public function store(){
        return $this->belongsTo('App\Models\User', 'store_id');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function deliveryaddress(){
        return $this->belongsTo('App\Models\CustomerAddress', 'address_id');
    }

    public function timeslot(){
        return $this->belongsTo('App\Models\TimeSlot', 'delivery_time_slot');
    }

    public function area(){
        return $this->belongsTo('App\Models\Area', 'area_id');
    }


}
