<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingItem extends Model
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

    public function progresses()
    {
        return $this->hasMany(ShippingProgress::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class);
    }
}
