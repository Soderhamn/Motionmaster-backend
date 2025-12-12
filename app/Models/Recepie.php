<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepie extends Model
{
    //

    protected $fillable = [
        'title',
        'short_description',
        'html_content',
        'premium_level',
    ];
}
