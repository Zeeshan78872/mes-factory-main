<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPacking extends Model
{
    use HasFactory;

    /**
     * Get the item associated
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the item associated
     */
    public function pictures()
    {
        return $this->hasMany(ProductPackingPicture::class, 'product_packing_id');
    }
}
