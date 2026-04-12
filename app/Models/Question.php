<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    protected $fillable = [
        'text',
        'questionnaire_id',
        'type',
        'image',
        'is_required',
        'options',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array',
    ];

    public function questionnaire() {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public static function getTypes(): array
    {
        return [
            'scale' => 'Шкала',
            'text' => 'Текст',
            'single_choice' => 'Одиночный выбор',
            'multiple_choice' => 'Множественный выбор',
        ];
    }

    public function getOptionsArray(): array
    {
        if (is_array($this->options)) {
            return $this->options;
        }
        if (is_string($this->options)) {
            $decoded = json_decode($this->options, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function setOptionsFromString(string $value): void
    {
        $lines = array_filter(array_map('trim', explode("\n", $value)));
        $this->options = array_values($lines);
    }

    public function getOptionsAsString(): string
    {
        return implode("\n", $this->getOptionsArray());
    }
}
