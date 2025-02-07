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
        Schema::create('log_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //The user that this comment belongs to
            $table->foreignId('training_log_id')->constrained()->onDelete('cascade'); //The training log that this comment belongs to
            $table->string('comment'); //Comment on the training log
            $table->integer('reply_to')->nullable(); //If this comment is a reply to another comment, this is the id of the comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_comments');
    }
};
