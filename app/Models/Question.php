<?php

namespace App\Models;

use App\Events\QuestionActive;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'topic_id',
        'question',
        'a',
        'b',
        'c',
        'd',
        'answer',
        'status',
        'question_order',
        'code_snippet',
        'answer_exp',
        'question_img',
        'question_video_link'
    ];

    /* protected $dispatchesEvents = [
        'active' => QuestionActive::class
    ]; */

    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\Topic');
    }
}