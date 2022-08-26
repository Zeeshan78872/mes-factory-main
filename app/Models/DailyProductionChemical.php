<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionChemical extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_production_id',
        'stock_card_id',
        'method',
        'quantity'
    ];

    public function dailyProduction()
    {
        return $this->belongsTo(DailyProduction::class);
    }

    public function chemical()
    {
        return $this->belongsTo(ProductStockCard::class, 'stock_card_id');
    }
}
