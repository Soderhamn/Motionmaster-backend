<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingGoal extends Model
{
    //Attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'progress',
        'target',
        'type',
        'archived',
        'type', //standard or template (a template can be copied into a new schedule)
        'template_id', //if this log is a copy of a template, this is the id of the template, nullable
        'premium_level', //if this is a premium schedule, this is the level of premium 0 = free, 1 = premium 1, 2 = premium 2 etc.
    ];


    //This belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
