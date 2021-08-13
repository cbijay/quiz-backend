<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService
{
    protected $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function get()
    {
        return $this->subject->get();
    }
}