<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingReplacementPart extends Model
{
    use HasFactory;

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
