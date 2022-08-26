<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionProgress extends Model
{
    use HasFactory;

    protected $table = 'daily_production_progresses';
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime:Y-m-d H:i:s',
        'ended_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function dailyProduction()
    {
        return $this->belongsTo(DailyProduction::class);
    }
}
