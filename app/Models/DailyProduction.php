<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProduction extends Model
{
    use HasFactory;

    public function stockCards()
    {
        return $this->hasMany(DailyProductionStockCard::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function workers()
    {
        return $this->hasMany(DailyProductionWorker::class);
    }

    public function machines()
    {
        return $this->hasMany(DailyProductionMachine::class);
    }

    public function progresses()
    {
        return $this->hasMany(DailyProductionProgress::class);
    }

    public function chemicals()
    {
        return $this->hasMany(DailyProductionChemical::class);
    }
}
