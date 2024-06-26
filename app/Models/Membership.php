<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class Membership extends Model
{
    use HasFactory,Active;


    protected $table='memberships';

    public $fillable=['name', 'title', 'description','price', 'cut_price', 'min_order', 'months','isactive'];
    protected $hidden = [
        'created_at','deleted_at','updated_at'
    ];
}
