<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDelivery extends Model
{
    use HasFactory;

    protected $table='daily_deliveries';

    protected $fillable =['order_id', 'user_id', 'product_id', 'quantity', 'delivery_date', 'delivery_time_slot', 'address_id', 'rider_id'];

}
