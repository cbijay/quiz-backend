<?php

namespace App\Services\Admin;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StudentService
{
    public function getStudent()
    {
        $student = User::where('role', '!=', 'A')->with('student')->get();
        return $student;
    }

    public function latestStudent()
    {
        $student = User::where('role', '!=', 'A')->latest()->get();
        return $student;
    }

    public function updateStatus($id, $status)
    {
        $user = User::where('id', $id)->update(['status' => $status]);

        return $user;
    }

    public function participants()
    {
        $students = User::where('role', '!=', 'A')->where('status', 1)->with('answers')->get();

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
            ];

            if (count($answers) > 0) {
                foreach ($answers as $answer) {
                    $topic = Topic::where('id', $answer->topic_id)->first();
                    $participant->per_q_mark = $topic->per_q_mark;
                }
            }

            $activeStudent->push($participant);
        }

        return $activeStudent;
    }
}