<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        return $this->model->get();
    }

    public function paginate($num)
    {
        return $this->model->paginate($num);
    }

    public function store(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function withById($id, $table)
    {
        return $this->model->where('id', $id)->with($table)->first();
    }

    public function update($id, array $attributes)
    {
        $item = $this->model->findOrFail($id);
        $item->fill($attributes);
        $item->save();
        //$item->update($attributes);
        return $item;
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}