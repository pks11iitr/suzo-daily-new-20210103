<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use App\Models\Traits\ReviewTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Active, DocumentUploadTrait, ReviewTrait;

    protected $hidden = ['created_at','deleted_at','updated_at'];

}
