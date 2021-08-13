<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'user_img'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function student()
    {
        return $this->hasOne('App\Models\Student');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Answer')->latest('created_at');
    }

    public function topic()
    {
        return $this->belongsToMany('App\Models\Topic', 'topic_user')
            ->withPivot('amount', 'transaction_id', 'status')
            ->withTimestamps();
    }

    public function studentAnswer($topicId)
    {
        return $this->answers()->where('topic_id', $topicId)->get();
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }
}
