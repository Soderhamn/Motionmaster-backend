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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->float('weight')->nullable();; //Vikt kg
            $table->float('height')->nullable();; //Längd
            $table->date('dob')->nullable();; //År, månad, dag, för att räkna ut ålder
            $table->enum('training_level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->string("sport")->nullable(); 
            $table->string('role')->default('user'); //user, admin
            $table->string('push_token')->nullable(); //Exponent push token
            $table->string('email_notifications')->default(1); //0 = off, 1 = on
            $table->string('device_type')->nullable(); //ios, android
            $table->string('external_auth_provider')->nullable(); //google, apple
            $table->string('external_auth_id')->nullable(); //googles, apples ID
            $table->string('profile_picture_url')->nullable(); //URL till profilbild, ANVÄNDS EJ, kan evt. användas i framtiden
            $table->string('email_verification_code')->nullable(); //Kod som skickas till användarens email för att verifiera email
            $table->string('password_reset_code')->nullable(); //Kod som skickas till användarens email för att återställa lösenord
            $table->date('password_reset_code_created_at')->nullable(); //När lösenordsåterställningskoden skapades
            $table->integer('premium_level')->default(0); //0 = free, 1 = premium level 1, 2 = premium level 2 etc.
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
