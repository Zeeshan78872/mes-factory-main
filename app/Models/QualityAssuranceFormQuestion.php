<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityAssuranceFormQuestion extends Model
{
    use HasFactory;

    public function form()
    {
        return $this->belongsTo(QualityAssuranceForm::class, 'qa_form_id');
    }
}
