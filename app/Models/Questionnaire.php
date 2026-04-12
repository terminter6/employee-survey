<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Questionnaire extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'hash',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = Str::random(32);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'hash';
    }

    public static function findByHash($hash)
    {
        return static::where('hash', $hash)->firstOrFail();
    }

    public function category()
    {
        return $this->belongsTo(QuestionnaireCategory::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function questionRandomize()
    {
        return $this->questions()->inRandomOrder()->get();
    }
    public function questionnaireResults()
    {
        return $this->hasMany(QuestionnaireResult::class);
    }

    public function getUrlAttribute(): string
    {
        return config('app.url') . '/questionnaire/' . $this->hash;
    }
}
