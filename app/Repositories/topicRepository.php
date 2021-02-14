<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Topic;

class TopicRepository extends BaseRepository
{
    public function __construct(Topic $topic)
    {
        parent::__construct($topic);
    }
}