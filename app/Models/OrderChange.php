<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderChange extends Model
{
    protected $table='orders_changes';

    protected $fillable=['refid', 'total_cost', 'status', 'payment_status', 'payment_mode', 'order_details_completed', 'booking_date', 'booking_time', 'user_id', 'name', 'email', 'mobile', 'address_id', 'lat', 'lang', 'is_instant', 'use_wallet', 'use_points', 'balance_used', 'points_used','schedule_type','order_place_state','coupon_applied', 'coupon_discount', 'delivery_charge', 'delivery_date', 'delivery_slot', 'delivered_at', 'cashback_given','store_id','is_express_delivery','cancel_reason','savings'];

}
