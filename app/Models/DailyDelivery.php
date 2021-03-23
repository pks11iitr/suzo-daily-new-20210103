<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class DailyDelivery extends Model
{
    use HasFactory;

    protected $table='daily_deliveries';

    protected $fillable =['order_id', 'detail_id', 'user_id', 'product_id', 'quantity', 'delivery_date', 'delivery_time_slot', 'address_id', 'rider_id'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function detail(){
        return $this->belongsTo('App\Models\OrderDetail', 'detail_id');
    }

}
