<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use Illuminate\Support\Str;

class QuestionnaireHashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Questionnaire::whereNull('hash')->each(function ($questionnaire) {
            $questionnaire->update([
                'hash' => Str::random(32),
            ]);
        });
    }
}
