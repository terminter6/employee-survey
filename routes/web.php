<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuestionnaireExportController;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'show'])->name('questionnaire.show');
Route::post('/questionnaire/{questionnaire}', [QuestionnaireController::class, 'store'])->name('questionnaire.store');
Route::get('/questionnaire/{questionnaire}/thanks', [QuestionnaireController::class, 'thanks'])->name('questionnaire.thanks');

Route::get('/admin/questionnaire/{questionnaire}/export', [QuestionnaireExportController::class, 'export'])
    ->name('questionnaire.export');

Route::post('/admin/questionnaire/questions', [QuestionController::class, 'store'])
    ->name('questionnaire.questions.store');
Route::put('/admin/questionnaire/questions/{question}', [QuestionController::class, 'update'])
    ->name('questionnaire.questions.update');
Route::post('/admin/questionnaire/questions/{question}/delete', [QuestionController::class, 'destroy'])
    ->name('questionnaire.questions.destroy');
