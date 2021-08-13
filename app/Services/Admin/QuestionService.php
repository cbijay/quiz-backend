<?php

namespace App\Services\Admin;

use App\Models\Question;

class QuestionService
{
    protected $question;

    public function __construct(Question $question) {
        $this->question = $question;
    }

    public function get()
    {
        return $this->question->get();
    }

    public function paginate($num)
    {
        return $this->question->paginate($num);
    }

    public function store(array $data)
    {
        return $this->question->create($data);
    }

    public function getById($id)
    {
        return $this->question->findOrFail($id);
    }

    public function withById($id, $table)
    {
        return $this->question->where('id', $id)->with($table)->first();
    }

    public function update($id, array $data)
    {
        return $this->question->find($id)->update($data);
        
    }

    public function destroy($id)
    {
        return $this->question->destroy($id);
    }
}