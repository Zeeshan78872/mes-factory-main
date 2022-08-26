<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBomMapping extends Model
{
    use HasFactory;

    /**
     * Get the item associated with the BOM Mapping.
     */
    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    /**
     * Get the item associated with the BOM Mapping.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
