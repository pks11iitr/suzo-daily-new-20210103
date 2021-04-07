<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{
    use HasFactory,Active,DocumentUploadTrait;

    protected $table='offer_details';

    protected $fillable=['title', 'image', 'description', 'isactive'];
}
