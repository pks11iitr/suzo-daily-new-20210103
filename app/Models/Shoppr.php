<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoppr extends Model
{
    use HasFactory;
    protected $table='shoppers';

    protected $fillable = ['name', 'lat','lang', 'isactive','location'];

    protected $hidden = ['deleted_at','updated_at','created_at'];

}
