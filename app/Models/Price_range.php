<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price_range extends Model
{
    use HasFactory;
    protected $table='price_range';
    protected $fillable = 
    [
    'brand_id',
    'value_type',
    'value',
    'range_from',
    'range_to'];


    public function brand()
    {
        return $this->hasOne(ProductCatalogueLanguage::class, 'product_catalogue_id','brand_id');
    }
}
