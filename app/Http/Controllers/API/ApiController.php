<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResult;
use App\Models\Answer;

class ApiController extends \App\Http\Controllers\Controller
{
    public function getQuestions($id)
    {
        $questionnaire = Questionnaire::with('questions')->findOrFail($id);
        $questions = $questionnaire->questionRandomize();
        return response()->json( $questions);
    }

    public function submitAnswers(Request $request, $id)
    {
        $questionnaire = Questionnaire::with('questions')->findOrFail($id);

        $result = QuestionnaireResult::create([
            'questionnaire_id' => $questionnaire->id,
        ]);

        $answers = $request->input('answers', []);
        foreach ($questionnaire->questions as $question) {
            $value = $answers[$question->id] ?? null;
            if ($value === null) continue;
            $data = [
                'questionnaire_result_id' => $result->id,
                'question_id' => $question->id,
            ];
            if ($question->type === 'scale') {
                $data['scale_value'] = $value;
            } elseif ($question->type === 'text') {
                $data['text_value'] = $value;
            }
            Answer::create($data);
        }

        return response()->json(['message' => 'Answers saved successfully!'], 200);
    }
}
