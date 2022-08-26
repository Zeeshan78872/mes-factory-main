<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderBomList extends Model
{
    use HasFactory;

    /**
     * Get the item associated with the BOM Mapping.
     */
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

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
