<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingLog extends Model
{
    //Attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'training_schedule_id',
        'date',
        'duration',
        'rating',
        'comment',
    ];


    //This belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //This also belongs to a TrainingSchedule
    public function trainingSchedule()
    {
        return $this->belongsTo(TrainingSchedule::class);
    }
}
