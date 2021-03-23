<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class BookDay extends Model
{
    use HasFactory;
    protected $table='days';
    protected $fillable =['name'];
    protected $hidden = ['created_at','deleted_at','updated_at'];

}
