<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableRemoveSurnameAddGender extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname'); // Ta bort kolumnen 'surname'
            $table->string('gender')->nullable(); // Lägg till kolumnen 'gender' som en string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable(); // Lägg tillbaka kolumnen 'surname'
            $table->dropColumn('gender'); // Ta bort kolumnen 'gender'
        });
    }
}