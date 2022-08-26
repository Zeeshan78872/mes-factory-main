<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionStockCard extends Model
{
    use HasFactory;

    protected $table = 'daily_production_stock_cards';

    public function dailyProduction()
    {
        return $this->belongsTo(DailyProduction::class);
    }

    public function stockCard()
    {
        return $this->belongsTo(ProductStockCard::class);
    }
}
