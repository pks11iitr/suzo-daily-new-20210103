<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use App\Models\Traits\ReviewTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BaseModel extends Model implements Auditable
{
    use Active, DocumentUploadTrait, ReviewTrait, \OwenIt\Auditing\Auditable;

    protected $hidden = ['created_at','deleted_at','updated_at'];

}
