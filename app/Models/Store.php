<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory,Active,DocumentUploadTrait;
    protected $table='stores';

    protected $fillable = ['location_id', 'lat','lang', 'isactive','store_name','store_type','is_sale','image'];

    protected $hidden = ['deleted_at','updated_at','created_at'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
    public function images(){
        return $this->hasMany('App\Models\Document', 'store_id');
    }
}
