<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\BaseRepository;

class MessageRepository extends BaseRepository
{
    public function __construct(Message $message)
    {
        parent::__construct($message);
    }
}