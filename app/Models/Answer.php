<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'topic_id', 'user_id', 'question_id', 'user_answer', 'answer'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic');
    }

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer');
    }
}
