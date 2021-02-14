<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Repositories\BaseRepository;

class AnswerRepository extends BaseRepository
{
    public function __construct(Answer $answer)
    {
        parent::__construct($answer);
    }
}