<?php

namespace App\Repositories;

use App\Models\Topic;
use App\Models\User;

class UserRepository
{
    protected $user, $topic;

    public function __construct(User $user, Topic $topic)
    {
        $this->user = $user;
        $this->topic = $topic;
    }

    public function getStudent()
    {
        return $this->user->where('role', '!=', 'A')->with('student')->get();
    }

    public function withById($id, $table)
    {
        return $this->user->where('id', $id)->with($table)->first();
    }

    public function updateStatus($id, $status)
    {
        return $this->user->findOrFail($id)->update(['status' => $status]);
    }

    public function latestStudent()
    {
        $student = $this->user->where('role', '!=', 'A')->latest()->get();
        return $student;
    }

    public function userAnswers($id)
    {
        $user = $this->user->where('id', $id)->with('answers')->first();
        $answers = isset($user->answers) ? $user->answers : [];

        return $answers;
    }

    public function participants()
    {
        $students = $this->user->where('role', '!=', 'A')->where('status', 1)->with('answers', 'topic')->get();
        $activeStudent = collect();

        foreach ($students as $student) {
            $answers = $student->answers;

            $participant = (object) [
                'id'    => $student->id,
                'name' => $student->name,
                'image' => $student->user_img,
                'per_q_mark' => 0,
                'isOnline' => $student->is_online,
                'answers' => $student->answers,
                'correct_answer' => 0,
                'score' => 0
            ];

            if (count($answers) > 0) {
                $topic = $this->topic->where('id', $answers[0]->topic_id)->first();

                if (isset($topic)) {
                    $participant->per_q_mark = $topic->per_q_mark;
                }

                foreach ($answers as $answer) {

                    if ($answer->user_answer === $answer->answer && isset($answer->topic)) {
                        $participant->correct_answer += 1;
                        $participant->score +=  1 * $answer->topic->per_q_mark;
                    }
                }
            }

            $activeStudent->push($participant);
        }

        return $activeStudent;
    }
}
