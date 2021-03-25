<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use Active;
    protected $table='area_list';

    protected $fillable=['name', 'isactive', 'store_id', 'city_id', 'rider_id'];

    public function store(){
        return $this->belongsTo('App\Models\User', 'store_id');
    }

    public function rider(){
        return $this->belongsTo('App\Models\Rider', 'rider_id');
    }
}
