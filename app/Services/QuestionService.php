<?php

namespace App\Services;

use App\Events\QuestionActive;
use App\Models\Question;
use App\Models\User;

class QuestionService
{
    public function activeQuestion()
    {
        $question = Question::where('status', 1)->orderBy('id', 'DESC')->first();
        $userAnswers = isset($question) ?? count($question->answers) > 0 ? $question->answers : [];

        $activeUser = collect();

        if ($question) {
            $activeQuestion = (object) [
                'id'    => isset($question) ? $question->id : '',
                'topic_id'  =>  isset($question) ? $question->topic_id : 0,
                'question' => isset($question) ? $question->question : '',
                'a' => isset($question) ? $question->a : '',
                'b' => isset($question) ? $question->b : '',
                'c' => isset($question) ? $question->c : '',
                'd' => isset($question) ? $question->d : '',
                'timer' => isset($question) ? $question->topic->timer : 0,
                'answer' => isset($question) ? $question->answer : '',
                'status' => isset($question) ? $question->status : 0,
                'users' => "",
            ];
        } else {
            $activeQuestion = '';
        }


        if (count($userAnswers) > 0) {
            foreach ($userAnswers as $answer) {
                $user = User::where('id', $answer->user_id)->first();

                $answerUser = (object)[
                    'name' => $user->name,
                    'user_img' => $user->user_img,
                    'answer' => $answer
                ];

                $activeUser->push($answerUser);
            }

            $activeQuestion->users = $activeUser;
        }

        return $activeQuestion;
    }
}