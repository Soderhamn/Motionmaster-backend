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
        //Träningsschema = Hur, när, hur mycket +  (visa video inbäddad direkt i schemat)
        Schema::create('training_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //The user that this schedule belongs to
            $table->string('title'); //Title of the schedule
            $table->string('description')->nullable(); //Description of the schedule
            $table->enum('status', ['active', 'completed', 'failed'])->default('active'); //Status of the schedule
            $table->enum('sport', ['running', 'cycling', 'swimming', 'gym', 'soccer', 'basketball', 'other'])->default('other'); //Sport of the schedule
            $table->date('start_date')->nullable(); //Start date of the schedule
            $table->date('end_date')->nullable(); //End date of the schedule
            $table->string('video_url')->nullable(); //URL to the video
            $table->enum('archived', ['yes', 'no'])->default('no'); //Archived schedule
            $table->enum('type', ['standard', 'template'])->default('standard'); //Type of schedule
            $table->foreignId('template_id')->nullable()->constrained('training_schedules')->onDelete('cascade'); //If this schedule is a copy of a template, this is the id of the template
            $table->integer('premium_level')->default(0); //Premium level of the schedule, 0 = free, 1 = premium level 1, 2 = premium level 2 etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};
