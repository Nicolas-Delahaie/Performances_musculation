<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -------------------------- CREATION DES TABLES --------------------------
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->date("date_perf")->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedSmallInteger("repetitions");
            $table->unsignedSmallInteger("charge")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("exercice_id");
        });
        Schema::create('muscles', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
        });
        Schema::create('muscles_travailles', function (Blueprint $table) {
            $table->id();
            $table->decimal("solicitation", 4, 3);
            $table->unsignedBigInteger("exercice_id");
            $table->unsignedBigInteger("muscle_id");
            $table->unique(["exercice_id", "muscle_id"]);
        });
        Schema::create('exercices', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->boolean("poidsDeCorps");
            $table->unsignedBigInteger("createur_id")->nullable();
        });
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->timestamps();
            $table->unsignedBigInteger("createur_id")->nullable();
        });
        Schema::create('exercices_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("exercice_id");
            $table->unsignedBigInteger("programme_id");
            $table->unique(["exercice_id", "programme_id"]);
        });


        // -------------------------- AJOUT DES LIAISONS --------------------------
        Schema::table('performances', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('exercice_id')->references('id')->on('exercices');
        });
        Schema::table('muscles_travailles', function (Blueprint $table) {
            $table->foreign('exercice_id')->references('id')->on('exercices');
            $table->foreign('muscle_id')->references('id')->on('muscles');
        });
        Schema::table('exercices', function (Blueprint $table) {
            $table->foreign('createur_id')->references('id')->on('users');
        });
        Schema::table('programmes', function (Blueprint $table) {
            $table->foreign('createur_id')->references('id')->on('users');
        });
        Schema::table('exercices_programmes', function (Blueprint $table) {
            $table->foreign('exercice_id')->references('id')->on('exercices');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'performances',
            'muscles',
            'muscles_travailles',
            'exercices',
            'programmes',
            'exercices_programmes',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};