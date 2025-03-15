<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price_group extends Model
{
    use HasFactory;
    
    protected $table = 'price_group';

    protected $fillable = [
        'name',
        'product_catalogue_id',
        'brand_id',
        'discount',
        'shipping',
        'exchange_rate'
    ];

    public function catalogue()
    {
        return $this->hasOne(ProductCatalogueLanguage::class, 'product_catalogue_id', 'product_catalogue_id');
    }

    public function brand()
    {
        return $this->hasOne(ProductCatalogueLanguage::class, 'product_catalogue_id','brand_id');
    }

}
