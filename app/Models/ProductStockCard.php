<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_card_number',
        'ordered_quantity',
        'available_quantity',
        'order_id',
        'job_product_id',
        'product_id',
        'date_in',
        'date_out',
        'is_rejected',
        'is_balance'
    ];

    /**
     * Get the item associated with the Stock Card.
     */
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

    /**
     * Get the item associated with the Stock Card.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the item associated with the Stock Card.
     */
    public function stock()
    {
        return $this->belongsTo(ProductStock::class, 'product_id', 'product_id');
    }

    /**
     * Get the item associated with the Stock Card.
     */
    public function jobProduct()
    {
        return $this->belongsTo(Product::class, 'job_product_id')->withDefault([
            'model_name' => ''
        ]);
    }
}
