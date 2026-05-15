<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionnaireCategory;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionnaireResult;
use App\Models\Answer;
use App\Models\EmployeeEmail;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        Answer::truncate();
        QuestionnaireResult::truncate();
        Question::truncate();
        Questionnaire::truncate();
        QuestionnaireCategory::truncate();
        EmployeeEmail::truncate();

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } else {
            DB::statement('PRAGMA foreign_keys = ON');
        }

        $this->command->info('Старые данные удалены!');

        $feedbackCategory = QuestionnaireCategory::create(['name' => 'Обратная связь']);
        $productCategory = QuestionnaireCategory::create(['name' => 'Оценка продукта']);
        $employeeCategory = QuestionnaireCategory::create(['name' => 'Оценка сотрудников']);
        $clientCategory = QuestionnaireCategory::create(['name' => 'Удовлетворённость клиентов']);

        $eventSurvey = Questionnaire::create([
            'name' => 'Отзыв о мероприятии',
            'category_id' => $feedbackCategory->id,
            'ends_at' => now()->setSeconds(0)->addDays(3),
        ]);

        Question::create(['text' => 'Оцените организацию мероприятия', 'questionnaire_id' => $eventSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените полезность контента', 'questionnaire_id' => $eventSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените работу спикеров', 'questionnaire_id' => $eventSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Что понравилось больше всего?', 'questionnaire_id' => $eventSurvey->id, 'type' => 'text']);
        Question::create(['text' => 'Предложения на будущее', 'questionnaire_id' => $eventSurvey->id, 'type' => 'text']);

        $productSurvey = Questionnaire::create([
            'name' => 'Оценка нового продукта',
            'category_id' => $productCategory->id,
            'ends_at' => now()->setSeconds(0)->addDays(30),
        ]);

        Question::create(['text' => 'Оцените качество продукта', 'questionnaire_id' => $productSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените соотношение цены и качества', 'questionnaire_id' => $productSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Порекомендуете ли вы продукт друзьям?', 'questionnaire_id' => $productSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Что можно улучшить в продукте?', 'questionnaire_id' => $productSurvey->id, 'type' => 'text']);

        $employeeSurvey = Questionnaire::create([
            'name' => 'Ежегодная оценка сотрудника',
            'category_id' => $employeeCategory->id,
            'ends_at' => now()->setSeconds(0)->addMonths(6),
        ]);

        Question::create(['text' => 'Оцените профессиональные навыки', 'questionnaire_id' => $employeeSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените коммуникабельность', 'questionnaire_id' => $employeeSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените ответственность', 'questionnaire_id' => $employeeSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Достижения за год', 'questionnaire_id' => $employeeSurvey->id, 'type' => 'text']);
        Question::create(['text' => 'Цели на следующий год', 'questionnaire_id' => $employeeSurvey->id, 'type' => 'text']);

        $purchaseSurvey = Questionnaire::create([
            'name' => 'Обратная связь после покупки',
            'category_id' => $clientCategory->id,
            'ends_at' => now()->setSeconds(0)->addDays(14),
        ]);

        Question::create(['text' => 'Оцените качество обслуживания', 'questionnaire_id' => $purchaseSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените скорость доставки', 'questionnaire_id' => $purchaseSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Соответствует ли товар описанию?', 'questionnaire_id' => $purchaseSurvey->id, 'type' => 'scale']);
        Question::create(['text' => 'Комментарий к покупке', 'questionnaire_id' => $purchaseSurvey->id, 'type' => 'text']);

        $result = QuestionnaireResult::create([
            'questionnaire_id' => $eventSurvey->id,
            'created_at' => now()->subDays(5)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
        ]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 1, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 2, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 3, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 4, 'text_value' => ['Спикеры', 'Контент', 'Организация'][rand(0, 2)]]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 5, 'text_value' => ['Больше таких мероприятий', 'Увеличить время'][rand(0, 1)]]);

        for ($i = 0; $i < 3; $i++) {
            $result = QuestionnaireResult::create([
                'questionnaire_id' => $eventSurvey->id,
                'created_at' => now()->subDays(4)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
            ]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 1, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 2, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 3, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 4, 'text_value' => ['Спикеры', 'Контент', 'Организация', 'Нетворкинг'][rand(0, 3)]]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 5, 'text_value' => ['Больше таких мероприятий', 'Увеличить время', 'Добавить практику'][rand(0, 2)]]);
        }

        for ($i = 0; $i < 2; $i++) {
            $result = QuestionnaireResult::create([
                'questionnaire_id' => $eventSurvey->id,
                'created_at' => now()->subDays(2)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
            ]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 1, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 2, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 3, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 4, 'text_value' => ['Спикеры', 'Контент', 'Организация', 'Нетворкинг', 'Всё понравилось'][rand(0, 4)]]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 5, 'text_value' => ['Больше таких мероприятий', 'Увеличить время', 'Добавить практику', 'Частить встречи'][rand(0, 3)]]);
        }

        $result = QuestionnaireResult::create([
            'questionnaire_id' => $eventSurvey->id,
            'created_at' => now()->subDay()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
        ]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 1, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 2, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 3, 'scale_value' => rand(3, 5)]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 4, 'text_value' => ['Спикеры', 'Контент', 'Организация'][rand(0, 2)]]);
        Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 5, 'text_value' => ['Больше таких мероприятий'][rand(0, 0)]]);

        for ($i = 0; $i < 2; $i++) {
            $result = QuestionnaireResult::create([
                'questionnaire_id' => $eventSurvey->id,
                'created_at' => now()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
            ]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 1, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 2, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 3, 'scale_value' => rand(3, 5)]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 4, 'text_value' => ['Спикеры', 'Контент', 'Организация', 'Нетворкинг'][rand(0, 3)]]);
            Answer::create(['questionnaire_result_id' => $result->id, 'question_id' => 5, 'text_value' => ['Больше таких мероприятий', 'Увеличить время', ''][rand(0, 2)]]);
        }

        EmployeeEmail::create(['email' => 'ivanov@company.ru', 'name' => 'Иванов Иван']);
        EmployeeEmail::create(['email' => 'petrov@company.ru', 'name' => 'Петров Пётр']);
        EmployeeEmail::create(['email' => 'sidorov@company.ru', 'name' => 'Сидоров Сидор']);
        EmployeeEmail::create(['email' => 'smirnova@company.ru', 'name' => 'Смирнова Анна']);
        EmployeeEmail::create(['email' => 'kozlova@company.ru', 'name' => 'Козлова Мария']);

        $this->command->info('Данные успешно созданы!');
        $this->command->info('Создано:');
        $this->command->info('  - 4 категории опросов');
        $this->command->info('  - 4 опроса');
        $this->command->info('  - 17 вопросов');
        $this->command->info('  - 5 прохождений опроса "Отзыв о мероприятии"');
        $this->command->info('  - 5 email сотрудников');
    }
}
