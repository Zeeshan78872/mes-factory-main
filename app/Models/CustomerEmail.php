<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEmail extends Model
{
    use HasFactory;

    protected $table = 'customer_emails';


    /**
     * Get the customer associated with the email.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
