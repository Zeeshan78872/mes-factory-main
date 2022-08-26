<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderPurchaseList extends Model
{
    use HasFactory;

    /**
     * Get the item associated with the Purchase Order.
     */
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

    /**
     * Get the item associated with the Purchase Order.
     */
    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    /**
     * Get the item associated with the Purchase Order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the item associated with the Purchase Order.
     */
    public function stockCard()
    {
        return $this->belongsTo(ProductStockCard::class, 'stock_card_id');
    }
}
