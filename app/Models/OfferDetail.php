<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OfferDetail extends Model
{
    use HasFactory,Active,DocumentUploadTrait;

    protected $table='offer_details';

    protected $fillable=['name', 'image', 'description', 'isactive'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
}
