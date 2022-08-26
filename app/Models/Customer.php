<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Get the Email IDs associated with the customer.
     */
    public function emailIds()
    {
        return $this->hasMany(CustomerEmail::class);
    }

    /**
     * Get the Phone Nos associated with the customer.
     */
    public function phoneNos()
    {
        return $this->hasMany(CustomerPhoneNo::class);
    }
}
