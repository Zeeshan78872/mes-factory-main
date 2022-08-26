<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPhoneNo extends Model
{
    use HasFactory;

    protected $table = 'customer_phones';


    /**
     * Get the customer associated with the phone no.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
