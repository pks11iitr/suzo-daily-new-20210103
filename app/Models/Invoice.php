<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    use DocumentUploadTrait;

    protected $table ='invoice';

    protected $fillable=['prefix','sequence','current_sequence', 'pan_gst', 'address','image','organization_name','t_n_c'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
}
