<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderReceivingList extends Model
{
    use HasFactory;

    /**
     * Get the item associated with this.
     */
    public function purchase()
    {
        return $this->belongsTo(JobOrderPurchaseList::class, 'purchase_id');
    }

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
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
