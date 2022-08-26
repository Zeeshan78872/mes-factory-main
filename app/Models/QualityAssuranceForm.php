<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityAssuranceForm extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(QualityAssuranceFormQuestion::class, 'qa_form_id');
    }
}
