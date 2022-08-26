<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityAssuranceAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quality_assurance_id',
        'qa_form_question_id',
        'answer',
        'cr',
        'mi',
        'mn'
    ];

    public function qualityAssurance()
    {
        return $this->belongsTo(QualityAssurance::class);
    }

    public function question()
    {
        return $this->belongsTo(QualityAssuranceFormQuestion::class, 'qa_form_question_id');
    }
}
