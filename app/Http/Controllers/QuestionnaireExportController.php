<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResult;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QuestionnaireExportController extends Controller
{
    public function export(Questionnaire $questionnaire)
    {
        $results = QuestionnaireResult::where('questionnaire_id', $questionnaire->id)
            ->with(['answers'])
            ->latest()
            ->get();

        $questions = Question::where('questionnaire_id', $questionnaire->id)
            ->orderBy('id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Результаты опроса: ' . $questionnaire->name);
        $sheet->mergeCells('A1:' . $this->getColumnLetter(count($questions) + 1) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerRow = 3;
        $sheet->setCellValue('A' . $headerRow, '№');
        $sheet->setCellValue('B' . $headerRow, 'Дата прохождения');

        $colIndex = 2;
        foreach ($questions as $question) {
            $colIndex++;
            $colLetter = $this->getColumnLetter($colIndex);
            $sheet->setCellValue($colLetter . $headerRow, $question->text);
        }

        $lastColLetter = $this->getColumnLetter($colIndex);
        $headerRange = 'A' . $headerRow . ':' . $lastColLetter . $headerRow;
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4A90E2');
        $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setWrapText(true);

        $row = $headerRow + 1;
        foreach ($results as $index => $result) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $result->created_at->format('d.m.Y H:i'));

            $colIndex = 2;
            foreach ($questions as $question) {
                $colIndex++;
                $colLetter = $this->getColumnLetter($colIndex);

                $answer = $result->answers->firstWhere('question_id', $question->id);
                $value = $answer?->text_value ?? $answer?->scale_value ?? '—';

                $sheet->setCellValue($colLetter . $row, $value);
            }

            $row++;
        }

        foreach (range('A', $lastColLetter) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $dataRange = 'A' . $headerRow . ':' . $lastColLetter . ($row - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $filename = 'results_' . $questionnaire->id . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function getColumnLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $letter = chr(65 + $remainder) . $letter;
            $index = intdiv($index - 1, 26);
        }
        return $letter;
    }
}
