<?php

namespace App\Services\Admin;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use App\Services\Admin\StudentService;

class ReportService
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function getReports($topicId)
    {
        $students = $this->studentService->getStudent();
        $c_que = Question::where('topic_id', $topicId)->count();

        $reportstudents = collect();

        foreach ($students as $student) {
            $studentAnswers = $student->studentAnswer($topicId);
            $filterStudent = [
                'name' => $student->name,
                'mobile' => $student->mobile,
                'questions' => $c_que,
                'answers' => $studentAnswers
            ];
            $reportstudents->push($filterStudent);
        }

        $reportstudents = $reportstudents->unique();
        return $reportstudents;
    }
}