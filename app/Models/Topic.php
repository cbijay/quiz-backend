<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title', 'per_q_mark', 'description', 'timer', 'show_ans', 'amount', 'status',
    ];

    public function question()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function answer()
    {
        return $this->hasOne('App\Models\Answer');
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'topic_user')
            ->withPivot('amount', 'transaction_id', 'status')
            ->withTimestamps();
    }
}