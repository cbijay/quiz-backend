<?php

namespace App\Repositories;

use App\Models\Topic;

class TopicRepository
{
    protected $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function questions($id)
    {
        $topic = $this->topic->where('id', $id)->with('question')->first();
        $questions = isset($topic->question) ? $topic->question : [];

        return $questions;
    }
}
