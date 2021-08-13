<?php

namespace App\Services\Admin;

use App\Models\Topic;

class TopicService
{
    protected $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function get()
    {
        return $this->topic->get();
    }

    public function paginate($num)
    {
        return $this->topic->paginate($num);
    }

    public function store(array $data)
    {
        return $this->topic->create($data);
    }

    public function getById($id)
    {
        return $this->topic->findOrFail($id);
    }

    public function update($id, array $data)
    {
        return $this->topic->find($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->topic->destroy($id);
    }
}