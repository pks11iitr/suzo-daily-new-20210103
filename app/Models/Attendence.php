<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory, DocumentUploadTrait;

    protected $table='attendences';

    protected $fillable=['lat', 'lang', 'map_address', 'user_id', 'image'];




}
