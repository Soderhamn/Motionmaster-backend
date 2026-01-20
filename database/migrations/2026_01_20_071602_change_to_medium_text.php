<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->mediumText('html_content')->change();
        });

        Schema::table('recepies', function (Blueprint $table) {
            $table->mediumText('html_content')->change();
        });

        Schema::table('training_schedules', function (Blueprint $table) {
            $table->mediumText('html_content')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->text('html_content')->change();
        });

        Schema::table('recepies', function (Blueprint $table) {
            $table->text('html_content')->change();
        });

        Schema::table('training_schedules', function (Blueprint $table) {
            $table->text('html_content')->change();
        });
    }
};