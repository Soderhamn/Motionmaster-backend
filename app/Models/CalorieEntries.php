<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalorieEntries extends Model
{
    use HasFactory;

    protected $fillable = [
        "foodName",
        "calories",
        "caloriesPer100g",
        "weight",
        "date",
        "user_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
