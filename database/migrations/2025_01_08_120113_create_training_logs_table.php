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
        Schema::create('training_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //The user that this log belongs to
            $table->foreignId('training_schedule_id')->nullable()->constrained()->onDelete('cascade'); //The training schedule that this log belongs to
            $table->date('date'); //Date of the training
            $table->integer('duration')->nullable(); //minutes
            $table->enum('rating', [1, 2, 3, 4, 5]); //Rating of the training session 1 = bad, 5 = excellent
            $table->string('comment')->nullable(); //Comment on the training session
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_logs');
    }
};
