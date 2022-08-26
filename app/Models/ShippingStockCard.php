<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingStockCard extends Model
{
    use HasFactory;

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function stockCard()
    {
        return $this->belongsTo(ProductStockCard::class, 'stock_card_id');
    }
}
