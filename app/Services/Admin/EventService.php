<?php

namespace App\Services\Admin;

use App\Models\Event;

class EventService
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
    
    public function get()
    {
        return $this->event->get();
    }

    public function paginate($num)
    {
        return $this->event->paginate($num);
    }

    public function store(array $data)
    {
        return $this->event->create($data);
    }

    public function getById($id)
    {
        return $this->event->findOrFail($id);
    }

    public function withById($id, $table)
    {
        return $this->event->where('id', $id)->with($table)->first();
    }

    public function update($id, array $data)
    {
        return $this->event->find($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->event->destroy($id);
    }
}