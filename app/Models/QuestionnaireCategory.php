<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function questionnaires() {
        return $this->hasMany(Questionnaire::class);
    }
}
