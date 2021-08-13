<?php

namespace App\Services\Admin;

use App\Models\User;

class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(array $data)
    {
        return $this->user->create($data);
    }

    public function getById($id)
    {
        return $this->user->findOrFail($id);
    }

    public function update($id, array $data)
    {
        return $this->user->find($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->user->destroy($id);
    }
}