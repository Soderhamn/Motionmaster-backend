<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //The user that this goal belongs to
            $table->string('title'); //Title of the goal
            $table->string('description')->nullable(); //Description of the goal
            $table->date('start_date')->nullable(); //Start date of the goal
            $table->date('end_date')->nullable(); //End date of the goal
            $table->enum('status', ['not_started', 'active', 'completed', 'failed'])->default('active'); //Status of the goal
            $table->integer('progress')->default(0); //Progress of the goal in percentage
            $table->integer('target')->default(100); //Target of the goal in percentage
            $table->enum('goal_type', ['weight_loss', 'endurance_gain', 'muscle_gain', 'strength_gain', 'flexibility_gain', 'other'])->default('other'); //Type of the goal: viktminskning, kondition, muskelökning, styrka, flexibilitet, annat
            $table->enum('archived', ['yes', 'no'])->default('no'); //Archived goal
            $table->enum('type', ['standard', 'template'])->default('standard'); //Type of schedule
            $table->foreignId('template_id')->nullable()->constrained('training_goals')->onDelete('cascade'); //If this goal is a copy of a template, this is the id of the template
            $table->integer('premium_level')->default(0); //Premium level of the schedule, 0 = free, 1 = premium level 1, 2 = premium level 2 etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_goals');
    }
};
