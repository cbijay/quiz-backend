<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Repositories\BaseRepository;

class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $subject)
    {
        parent::__construct($subject);
    }
}