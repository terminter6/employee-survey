<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResult;
use App\Models\Answer;

class QuestionnaireController extends Controller
{
    public function show(Questionnaire $questionnaire)
    {
        if (! $questionnaire->is_active) {
            return view('questionnaire.inactive', compact('questionnaire'));
        }

        $questions = $questionnaire->questionRandomize();
        return view('questionnaire.questionnaire', compact('questionnaire', 'questions'));
    }

    public function store(Request $request, Questionnaire $questionnaire)
    {
        if (! $questionnaire->is_active) {
            return view('questionnaire.inactive', compact('questionnaire'));
        }

        $request->validate([
            'g-recaptcha-response' => ['required'],
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (!$result || !($result['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'Ошибка проверки reCAPTCHA. Пожалуйста, попробуйте снова.']);
        }

        $questionnaire->load('questions');

        $questionnaireResult = QuestionnaireResult::create([
            'questionnaire_id' => $questionnaire->id,
        ]);

        $answers = $request->input('answers', []);
        foreach ($questionnaire->questions as $question) {
            $value = $answers[$question->id] ?? null;
            if ($value === null) continue;

            if ($question->type === 'multiple_choice') {
                if (is_array($value)) {
                    foreach ($value as $selectedOption) {
                        Answer::create([
                            'questionnaire_result_id' => $questionnaireResult->id,
                            'question_id' => $question->id,
                            'text_value' => $selectedOption,
                        ]);
                    }
                }
            } else {
                $data = [
                    'questionnaire_result_id' => $questionnaireResult->id,
                    'question_id' => $question->id,
                ];
                if ($question->type === 'scale') {
                    $data['scale_value'] = $value;
                } elseif (in_array($question->type, ['text', 'single_choice'])) {
                    $data['text_value'] = $value;
                }
                Answer::create($data);
            }
        }

        return redirect()->route('questionnaire.thanks', $questionnaire->hash);
    }

    public function thanks(Questionnaire $questionnaire)
    {
        return view('questionnaire.thanks', compact('questionnaire'));
    }

    public function generateLink(Questionnaire $questionnaire)
    {
        return response()->json([
            'url' => $questionnaire->url,
        ]);
    }
}
