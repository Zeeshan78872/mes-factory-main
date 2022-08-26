<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    use HasFactory;

    /**
     * Get the site associated with the item.
     */
    public function site()
    {
        return $this->belongsTo(MultiSite::class);
    }

    /**
     * Get the location associated with the item.
     */
    public function location()
    {
        return $this->belongsTo(SiteLocation::class, 'site_location_id');
    }

    /**
     * Get the department associated with the item.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the stockCard associated with the item.
     */
    public function stockCard()
    {
        return $this->belongsTo(ProductStockCard::class, 'stock_card_id');
    }
}
