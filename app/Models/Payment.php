<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_id', 'subjects', 'amount',
        'currency', 'status', 'user_id'
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
