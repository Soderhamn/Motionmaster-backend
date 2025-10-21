<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStrengthTrainingToSportEnumInTrainingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE training_schedules MODIFY sport ENUM('running', 'cycling', 'swimming', 'gym', 'soccer', 'walking', 'crossfit', 'strength_training', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE training_schedules MODIFY sport ENUM('running', 'cycling', 'swimming', 'gym', 'soccer', 'walking', 'crossfit', 'other') DEFAULT 'other'");
    }
}