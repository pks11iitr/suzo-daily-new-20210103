<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table='customer_address';
    protected $fillable=['user_id','first_name','last_name','mobile_no','email','house_no','appertment_name','street','landmark','area','city','pincode','address_type','other_text', 'lat','lang','map_address','delivery_active'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

}
