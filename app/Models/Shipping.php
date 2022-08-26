<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

    public function stockCards()
    {
        return $this->hasMany(ProductStockCard::class, 'stock_card_id');
    }

    public function leftOvers()
    {
        return $this->hasMany(ShippingLeftOver::class);
    }

    public function replacementParts()
    {
        return $this->hasMany(ShippingReplacementPart::class);
    }

    public function items()
    {
        return $this->hasMany(ShippingItem::class);
    }
}
