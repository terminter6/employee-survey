<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireMailController;

Route::middleware(['web', 'moonshine'])->group(function () {
    Route::get('/admin/questionnaire-mail/{questionnaire}', [QuestionnaireMailController::class, 'showForm'])
        ->name('questionnaire.mail.form');
    Route::post('/admin/questionnaire-mail/{questionnaire}', [QuestionnaireMailController::class, 'send'])
        ->name('questionnaire.mail.send');
    
    // Именованные маршруты для страниц QuestionnaireResource
    Route::get('/admin/resource/questionnaire-resource/form-page/{resourceItem?}', function ($resourceItem = null) {
        return redirect('/admin/resource/questionnaire-resource/crud' . ($resourceItem ? '/' . $resourceItem . '/edit' : '/create'));
    })->name('questionnaire-resource.form-page');
    
    Route::get('/admin/resource/questionnaire-resource/results-page', function () {
        return redirect('/admin/page/questionnaire-resource/results-page');
    })->name('questionnaire-resource.results-page');
});
