<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderProduct extends Model
{
    use HasFactory;


    /**
     * Get the products associated with the Job Order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function packingPictures()
    {
        return $this->hasMany(JobOrderProductPackingPicture::class, 'job_order_product_id');
    }
}
