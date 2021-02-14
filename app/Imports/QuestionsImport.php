<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    private $topic_id;

    public function __construct($topicId)
    {
        $this->topic_id = $topicId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Question([
            'topic_id'  =>  $this->topic_id,
            'question'  =>  $row['question'],
            'a' =>  $row['a'],
            'b' =>  $row['b'],
            'c' =>  $row['c'],
            'd' =>  $row['d'],
            'answer'    =>  $row['answer'],
            'status'    =>  $row['status'] == 'Active' ? 1 : 0,
            'question_order'    =>  $row['question_order'],
            'code_snippet'  =>  $row['code_snippet'],
            'answer_exp'    =>  $row['answer_exp']
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}