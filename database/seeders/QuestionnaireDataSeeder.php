<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionnaireCategory;
use App\Models\Questionnaire;
use App\Models\Question;

class QuestionnaireDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Категории
        $satisfactionCategory = QuestionnaireCategory::create(['name' => 'Удовлетворённость клиентов']);
        $feedbackCategory = QuestionnaireCategory::create(['name' => 'Обратная связь']);
        $employeeCategory = QuestionnaireCategory::create(['name' => 'Оценка сотрудников']);
        $productCategory = QuestionnaireCategory::create(['name' => 'Оценка продукта']);

        // Опрос 1: Удовлетворённость клиентов
        $customerSatisfaction = Questionnaire::create([
            'name' => 'Оценка удовлетворённости клиентов',
            'category_id' => $satisfactionCategory->id,
        ]);

        Question::create(['text' => 'Оцените качество нашего обслуживания', 'questionnaire_id' => $customerSatisfaction->id, 'type' => 'scale']);
        Question::create(['text' => 'Насколько вы довольны скоростью ответа?', 'questionnaire_id' => $customerSatisfaction->id, 'type' => 'scale']);
        Question::create(['text' => 'Порекомендуете ли вы нас друзьям?', 'questionnaire_id' => $customerSatisfaction->id, 'type' => 'scale']);
        Question::create(['text' => 'Что можно улучшить в нашей работе?', 'questionnaire_id' => $customerSatisfaction->id, 'type' => 'text']);

        // Опрос 2: Обратная связь после покупки
        $feedback = Questionnaire::create([
            'name' => 'Обратная связь после покупки',
            'category_id' => $feedbackCategory->id,
        ]);

        Question::create(['text' => 'Оцените качество товара', 'questionnaire_id' => $feedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Соответствует ли товар описанию?', 'questionnaire_id' => $feedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените упаковку товара', 'questionnaire_id' => $feedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Ваши пожелания и комментарии', 'questionnaire_id' => $feedback->id, 'type' => 'text']);

        // Опрос 3: Оценка сотрудников
        $employeeReview = Questionnaire::create([
            'name' => 'Ежегодная оценка сотрудника',
            'category_id' => $employeeCategory->id,
        ]);

        Question::create(['text' => 'Оцените профессиональные качества', 'questionnaire_id' => $employeeReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените коммуникативные навыки', 'questionnaire_id' => $employeeReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените ответственность и пунктуальность', 'questionnaire_id' => $employeeReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Достижения за год', 'questionnaire_id' => $employeeReview->id, 'type' => 'text']);
        Question::create(['text' => 'Цели на следующий год', 'questionnaire_id' => $employeeReview->id, 'type' => 'text']);

        // Опрос 4: Оценка продукта
        $productReview = Questionnaire::create([
            'name' => 'Оценка нового продукта',
            'category_id' => $productCategory->id,
        ]);

        Question::create(['text' => 'Оцените удобство использования', 'questionnaire_id' => $productReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените соотношение цена/качество', 'questionnaire_id' => $productReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените дизайн продукта', 'questionnaire_id' => $productReview->id, 'type' => 'scale']);
        Question::create(['text' => 'Какие функции хотелось бы добавить?', 'questionnaire_id' => $productReview->id, 'type' => 'text']);
        Question::create(['text' => 'Общие впечатления от продукта', 'questionnaire_id' => $productReview->id, 'type' => 'text']);

        // Опрос 5: Опрос о мероприятии
        $eventFeedback = Questionnaire::create([
            'name' => 'Отзыв о мероприятии',
            'category_id' => $feedbackCategory->id,
        ]);

        Question::create(['text' => 'Оцените организацию мероприятия', 'questionnaire_id' => $eventFeedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените полезность контента', 'questionnaire_id' => $eventFeedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Оцените работу спикеров', 'questionnaire_id' => $eventFeedback->id, 'type' => 'scale']);
        Question::create(['text' => 'Что понравилось больше всего?', 'questionnaire_id' => $eventFeedback->id, 'type' => 'text']);
        Question::create(['text' => 'Предложения на будущее', 'questionnaire_id' => $eventFeedback->id, 'type' => 'text']);
    }
}
