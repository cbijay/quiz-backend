<?php

namespace App\Repositories;

use App\Models\Question;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Models\Topic;
use App\Models\User;

class QuestionRepository
{
    protected $question, $topic, $user;

    public function __construct(Question $question, Topic $topic, User $user)
    {
        $this->question = $question;
        $this->topic = $topic;
        $this->user = $user;
    }

    public function askedQuestion()
    {
        $questions = $this->topic->with(['question' => function ($query) {
            $query->where('status', '!=', 0)->select('topic_id', 'question_order', 'status');
        }])->get();

        $questionCount = $this->question->where('status', '!=', 0)->count();

        $questionData = ['questions' => $questions, 'count' =>  $questionCount];

        return $questionData;
    }

    public function getTopics()
    {
        $topics = $this->topic->with('question')->get();
        return $topics;
    }

    public function getByTopics($topicId)
    {
        $questions = $this->question->where('topic_id', $topicId)->get();
        return $questions;
    }

    public function importExcel($topicId, $file)
    {
        $excelImport = Excel::import(new QuestionsImport($topicId), $file);
        return $excelImport;
    }

    public function answers($id)
    {
        $question = $this->question->where('id', $id)->with('answers')->first();
        $answers = isset($question->answers) ? $question->answers : [];

        return $answers;
    }

    public function questionActive($id)
    {
        $question = $this->question->find($id);
        $question->status = 1;
        $question->save();

        return $question;
    }

    public function updateStatus($id, $status)
    {

        $question = $this->question->where('id', $id)->update(['status' => $status]);

        return $question;
    }

    public function openQuestion($id, $status)
    {
        $question = $this->question->find($id);
        $question->status = $status;
        $question->reset = $status == 2 ? 0 : 1;
        $question->save();

        return $question;
    }

    public function resetTimer($id, $status)
    {
        $question = $this->question->find($id);
        $question->reset = $status;
        $question->save();

        return $question;
    }

    public function timeOutQuestion()
    {
        $question = $this->question->orderBy('updated_at', 'DESC')->first();
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
                $user = $this->user->where('id', $answer->user_id)->first();

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
