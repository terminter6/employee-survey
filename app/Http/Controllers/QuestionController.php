<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'questionnaire_id' => ['required', 'exists:questionnaires,id'],
            'text' => ['required', 'string'],
            'type' => ['required', 'in:scale,text,single_choice,multiple_choice'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array'],
        ]);

        $data = $request->only(['questionnaire_id', 'text', 'type', 'is_required']);
        $data['is_required'] = $request->boolean('is_required');

        if ($request->filled('options')) {
            $options = $request->input('options');
            if (is_string($options)) {
                $decoded = json_decode($options, true);
                $options = is_array($decoded) ? $decoded : [];
            }
            if (is_array($options)) {
                $values = [];
                foreach ($options as $option) {
                    if (is_array($option)) {
                        foreach (array_values($option) as $val) {
                            if (is_string($val) && trim($val) !== '') {
                                $values[] = trim($val);
                                break;
                            }
                        }
                    } elseif (is_string($option) && trim($option) !== '') {
                        $values[] = trim($option);
                    }
                }
                $data['options'] = !empty($values) ? json_encode(array_values($values)) : null;
            }
        } else {
            $data['options'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('questions', 'public');
        }

        Question::create($data);

        return redirect()
            ->back()
            ->with('moonshine_notification', [
                'type' => 'success',
                'message' => 'Вопрос создан',
            ]);
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'text' => ['required', 'string'],
            'type' => ['required', 'in:scale,text,single_choice,multiple_choice'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array'],
        ]);

        $data = $request->only(['text', 'type', 'is_required']);
        $data['is_required'] = $request->boolean('is_required');

        if ($request->filled('options')) {
            $options = $request->input('options');
            if (is_string($options)) {
                $decoded = json_decode($options, true);
                $options = is_array($decoded) ? $decoded : [];
            }
            if (is_array($options)) {
                $values = [];
                foreach ($options as $option) {
                    if (is_array($option)) {
                        foreach (array_values($option) as $val) {
                            if (is_string($val) && trim($val) !== '') {
                                $values[] = trim($val);
                                break;
                            }
                        }
                    } elseif (is_string($option) && trim($option) !== '') {
                        $values[] = trim($option);
                    }
                }
                $data['options'] = !empty($values) ? json_encode(array_values($values)) : null;
            }
        } else {
            $data['options'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('questions', 'public');
        }

        $question->update($data);

        return redirect()
            ->back()
            ->with('moonshine_notification', [
                'type' => 'success',
                'message' => 'Вопрос обновлён',
            ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return back()->with('moonshine_notification', [
            'type' => 'success',
            'message' => 'Вопрос удалён',
        ]);
    }
}
