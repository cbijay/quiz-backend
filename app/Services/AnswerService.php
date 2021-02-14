<?php

namespace App\Services;

use App\Models\Answer;

class AnswerService
{
    public function participantAnswer($input)
    {
        $answer = Answer::where('user_id', $input['user_id'])
            ->where('question_id', $input['question_id'])->first();

        return $answer;
    }
}