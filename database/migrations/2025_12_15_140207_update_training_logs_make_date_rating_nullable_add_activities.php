<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrainingLogsMakeDateRatingNullableAddActivities extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('training_logs', function (Blueprint $table) {
            // Gör 'date' nullable
            $table->date('date')->nullable()->change();
            // Gör 'rating' nullable
            $table->enum('rating', [1, 2, 3, 4, 5])->nullable()->change();
            // Lägg till 'activities' som json-fält
            $table->json('activities')->nullable()->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_logs', function (Blueprint $table) {
            // Ta bort 'activities'
            $table->dropColumn('activities');
            // Gör 'date' och 'rating' icke-nullable igen
            $table->date('date')->nullable(false)->change();
            $table->enum('rating', [1, 2, 3, 4, 5])->nullable(false)->change();
        });
    }
}