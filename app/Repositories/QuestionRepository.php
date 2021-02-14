<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Question;

class QuestionRepository extends BaseRepository
{
    public function __construct(Question $question)
    {
        parent::__construct($question);
    }
}