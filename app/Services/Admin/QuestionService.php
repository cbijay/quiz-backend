<?php

namespace App\Services\Admin;

use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class QuestionService
{
    public function askedQuestion()
    {
        $questions = Question::where('status', '!=', 0)->get();

        return $questions;
    }

    public function getTopics()
    {
        $topics = Topic::with('question')->get();
        return $topics;
    }

    public function getByTopics($topicId)
    {
        $questions = Question::where('topic_id', $topicId)->get();
        return $questions;
    }

    public function importExcel($topicId, $file)
    {
        $excelImport = Excel::import(new QuestionsImport($topicId), $file);
        return $excelImport;
    }

    public function questionActive($id)
    {
        $question = Question::find($id);
        $question->status = 1;
        $question->save();

        return $question;
    }

    public function updateStatus($id, $status)
    {
        $question = Question::where('id', $id)->update(['status' => $status]);

        return $question;
    }

    public function openQuestion($id, $status)
    {
        $question = Question::find($id);
        $question->status = $status;
        $question->reset = $status == 2 ? 0 : 1;
        $question->save();

        return $question;
    }

    public function resetTimer($id, $status)
    {
        $question = Question::find($id);
        $question->reset = $status;
        $question->save();

        return $question;
    }

    public function timeOutQuestion()
    {
        $question = Question::orderBy('updated_at', 'DESC')->first();
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
                'users' => [],
            ];
        } else {
            $activeQuestion = '';
        }


        if (count($userAnswers) > 0) {
            foreach ($userAnswers as $answer) {
                $user = User::where('id', $answer->user_id)->first();

                $answerUser = (object)[
                    'id'    =>  $user->id,
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