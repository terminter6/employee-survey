<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'questionnaire_result_id',
        'question_id',
        'scale_value',
        'text_value',
    ];

    public function questionnaireResult()
    {
        return $this->belongsTo(QuestionnaireResult::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function getCombinedAnswerAttribute()
    {
        if (!empty($this->text_value)) {
            return $this->text_value;
        } elseif (!is_null($this->scale_value)) {
            return (string) $this->scale_value;
        }

        return '-';
    }
}
