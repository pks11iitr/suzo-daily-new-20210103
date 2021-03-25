<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    protected $table='return_product';

    protected $fillable=['order_id', 'entity_id','entity_type', 'size_id', 'image', 'name', 'price', 'cut_price', 'quantity', 'rider_id', 'store_id', 'ref_id', 'reason'];

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function storename(){
        return $this->belongsTo('App\Models\User', 'store_id');
    }
    public function size(){

        return $this->belongsTo('App\Models\Size', 'size_id');

    }
}
