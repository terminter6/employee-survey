<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionnaire;
use App\Models\EmployeeEmail;
use Illuminate\Support\Facades\Mail;
use MoonShine\Support\Enums\ToastType;

class QuestionnaireMailController extends Controller
{
    public function showForm(Questionnaire $questionnaire)
    {

        $url = '/admin/resource/questionnaire-resource/mail-form-page/' . $questionnaire->hash;
        return redirect($url);
    }

    public function send(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $emails = $request->input('emails', '');
        if (empty($emails)) {
            return back()->withErrors(['emails' => 'Список сотрудников пуст']);
        }

        $emails = array_map('trim', explode(',', $emails));
        $emails = array_filter($emails);

        if (empty($emails)) {
            return back()->withErrors(['emails' => 'Введите хотя бы один email']);
        }

        $surveyUrl = $questionnaire->url;
        $subject = $request->subject;
        $message = $request->message;

        $failedEmails = [];

        foreach ($emails as $email) {
            try {
                Mail::raw("{$message}\n\nПройти опрос: {$surveyUrl}", function ($m) use ($email, $subject) {
                    $m->to($email)
                      ->from(config('mail.from.address', 'terminters@yandex.ru'), config('mail.from.name', 'Nethammer'))
                      ->subject($subject);
                });
            } catch (\Exception $e) {
                $failedEmails[] = $email;
            }
        }

        if (empty($failedEmails)) {
            toast('Опрос успешно отправлен на ' . count($emails) . ' email(ов)', type: ToastType::SUCCESS);
            return redirect('/admin/resource/questionnaire-resource/questionnaire-index-page');
        }

        toast('Не удалось отправить на: ' . implode(', ', $failedEmails), type: ToastType::ERROR);
        return redirect('/admin/resource/questionnaire-resource/questionnaire-index-page');
    }
}
