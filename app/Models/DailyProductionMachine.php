<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionMachine extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_production_id',
        'machine_id'
    ];

    public function dailyProduction()
    {
        return $this->belongsTo(DailyProduction::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
