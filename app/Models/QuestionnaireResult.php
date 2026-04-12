<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireResult extends Model
{
    protected $fillable = [
        'questionnaire_id',
    ];

    public function questionnaire() {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
