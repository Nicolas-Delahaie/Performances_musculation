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
        Schema::disableForeignKeyConstraints();

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
            $table->dateTime("date_perf");
            $table->unsignedSmallInteger("repetitions");
            $table->unsignedSmallInteger("charge")->nullable();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('exercice_id')->constrained();
        });
        Schema::create('muscles', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
        });
        Schema::create('muscles_travailles', function (Blueprint $table) {
            $table->id();
            $table->decimal("solicitation", 4, 3);

            $table->foreignId('exercice_id')->constrained();
            $table->foreignId('muscle_id')->constrained();
            $table->unique(["exercice_id", "muscle_id"]);
        });
        Schema::create('exercices', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->boolean("poidsDeCorps");
            $table->foreignId('createur_id')->nullable()->constrained(table: 'users')->nullable();
            ;
        });
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->timestamps();
            $table->foreignId('createur_id')->nullable()->constrained(table: 'users');
            ;
        });
        Schema::create('exercices_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercice_id')->constrained();
            $table->foreignId('programme_id')->constrained();
            $table->unique(["exercice_id", "programme_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableforeignIdKeyConstraints();
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