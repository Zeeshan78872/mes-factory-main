<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    use HasFactory;


    /**
     * Get the customer associated with the Job Order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the site associated with the Job Order.
     */
    public function site()
    {
        return $this->belongsTo(MultiSite::class);
    }

    /**
     * Get the createdBy associated with the Job Order.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updatedBy associated with the Job Order.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the products associated with the Job Order.
     */
    public function jobProducts()
    {
        return $this->hasMany(JobOrderProduct::class, 'order_id')->orderBy('product_id', 'ASC');
    }

    /**
     * Get the Bom Item associated with the Job Order.
     */
    public function bomItems()
    {
        return $this->hasMany(JobOrderBomList::class, 'order_id');
    }

    /**
     * Get the Shipping associated with the Job Order.
     */
    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id');
    }
}
