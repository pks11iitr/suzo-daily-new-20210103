<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table='countries';

    public function states(){
        return $this->hasMany('App\Models\State', 'country_id');
    }
}
