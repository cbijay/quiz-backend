<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function get();

    public function paginate($num);

    public function store(array $attributes);

    public function getById($id);

    public function update($id, array $attributes);

    public function destroy($id);
}