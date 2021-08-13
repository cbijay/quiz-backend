<?php

namespace App\Events;

use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminQuestionTimeUp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $question;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->question = Question::where('status', 1)->latest()->first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('adminQuestionTimeUp', $this->question);
    }

    public function broadcastToEveryone()
    {
        return [
            'adminQuestionTimeUp' => $this->question
        ];
    }
}
