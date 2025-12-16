<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToTrainingLogsTable extends Migration
{
    public function up(): void
    {
        Schema::table('training_logs', function (Blueprint $table) {
            $table->string('type')->default('standard')->after('activities');
        });
    }

    public function down(): void
    {
        Schema::table('training_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}