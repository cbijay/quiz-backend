<?php

namespace App\Services\Admin;

use App\Models\Student;

class StudentService
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function paginate($num)
    {
        return $this->student->paginate($num);
    }

    public function store(array $data)
    {
        return $this->student->create($data);
    }

    public function update($id, array $data)
    {
        return $this->student->find($id)->update($data);
    }
}