<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogComment extends Model
{
    //Attributes that are mass assignable
    protected $fillable = [
        'comment', 
        'user_id', 
        'training_log_id',
        'reply_to'
    ];

    //This belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //This belongs to a training log
    public function trainingLog()
    {
        return $this->belongsTo(TrainingLog::class);
    }
}
