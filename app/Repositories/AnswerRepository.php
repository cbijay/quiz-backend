<?php

namespace App\Repositories;

use App\Models\Answer;

class AnswerRepository
{
    protected $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function participantAnswer($input)
    {
        $answer = $this->answer->where('user_id', $input['user_id'])
            ->where('question_id', $input['question_id'])->first();

        return $answer;
    }
}
