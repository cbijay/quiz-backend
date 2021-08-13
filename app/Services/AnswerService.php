<?php

namespace App\Services;

use App\Models\Answer;

class AnswerService
{
    protected $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function store(array $data)
    {
        return $this->answer->create($data);
    }
}