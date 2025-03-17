<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_brand extends Model
{
    use HasFactory;
    protected $table='sub_brands';
    protected $fillable = 
    [
    'brand_id',
    'name',
    ];public $timestamps = false;
}
