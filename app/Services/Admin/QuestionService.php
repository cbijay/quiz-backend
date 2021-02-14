<?php

namespace App\Services\Admin;

use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\Topic;
use Maatwebsite\Excel\Facades\Excel;

class QuestionService
{
    public function getTopics()
    {
        $topics = Topic::with('question')->get();
        return $topics;
    }

    public function getByTopics($topicId)
    {
        $questions = Question::where('topic_id', $topicId)->get();
        return $questions;
    }

    public function importExcel($topicId, $file)
    {
        $excelImport = Excel::import(new QuestionsImport($topicId), $file);
        return $excelImport;
    }

    public function updateStatus($id)
    {
        $question = Question::find($id);
        $question->status = 1;
        $question->save();

        return $question;
    }
}