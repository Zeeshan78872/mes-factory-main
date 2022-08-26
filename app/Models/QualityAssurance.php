<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityAssurance extends Model
{
    use HasFactory;

    public function stockCard()
    {
        return $this->belongsTo(ProductStockCard::class, 'stock_card_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function inspectionBy()
    {
        return $this->belongsTo(User::class, 'qa_by');
    }

    public function form()
    {
        return $this->hasMany(QualityAssuranceForm::class, 'qa_type', 'qa_type');
    }

    public function answers()
    {
        return $this->hasMany(QualityAssuranceAnswer::class);
    }
}
