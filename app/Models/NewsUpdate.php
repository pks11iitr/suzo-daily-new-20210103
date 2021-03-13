<?php

namespace App\Models;
use App\Models\BaseModel as Model;
use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Support\Facades\Storage;

class NewsUpdate extends Model
{

    use Active;
    protected $table='news';

    protected $hidden = ['deleted_at','updated_at'];

    protected $fillable = ['image', 'description', 'isactive','title','short_description'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return '';
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/y', strtotime($value));
    }
}
