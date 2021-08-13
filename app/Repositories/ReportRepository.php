<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\Answer;
use App\Repositories\UserRepository;

class ReportRepository
{
    private $userRepository, $question, $answer;

    public function __construct(UserRepository $userRepository, Question $question, Answer $answer)
    {
        $this->userRepository = $userRepository;
        $this->question = $question;
        $this->answer = $answer;
    }

    public function getReports($topicId)
    {
        $students = $this->userRepository->getStudent();
        $c_que = $this->question->where('topic_id', $topicId)->count();

        $reportstudents = collect();

        foreach ($students as $student) {
            $studentAnswers = $student->studentAnswer($topicId);
            if (count($studentAnswers) > 0) {
                $filterStudent = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'questions' => $c_que,
                    'answers' => $studentAnswers
                ];
                $reportstudents->push($filterStudent);
            }
        }

        return $reportstudents;
    }

    public function deleteUserAnswer($topicId, $userId)
    {
        $answers = $this->answer->where('user_id', $userId)->where('topic_id', $topicId)->get();

        $deletedAnswer = 0;

        foreach ($answers as $answer) {
            $deletedAnswer = $answer->delete();
        }

        return $deletedAnswer;
    }
}
