<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_production_id',
        'worker_id'
    ];

    public function dailyProduction()
    {
        return $this->belongsTo(DailyProduction::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class);
    }
}
