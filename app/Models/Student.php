<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $fillable = [
        'grade', 'age', 'parents_name', 'city', 'address', 'phone_number', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'students_subjects');
    }
}