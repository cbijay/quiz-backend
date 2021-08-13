<?php

namespace App\Services\Admin;

use App\Models\Message;

class MessageService
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
    
    public function get()
    {
        return $this->message->get();
    }

    public function paginate($num)
    {
        return $this->message->paginate($num);
    }

    public function store(array $data)
    {
        return $this->message->create($data);
    }

    public function getById($id)
    {
        return $this->message->findOrFail($id);
    }

    public function withById($id, $table)
    {
        return $this->message->where('id', $id)->with($table)->first();
    }

    public function update($id, array $data)
    {
        return $this->message->find($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->message->destroy($id);
    }
}