<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_session_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // ej. "Press banca", "Sentadilla"
            $table->unsignedTinyInteger('sets')->nullable();
            $table->unsignedTinyInteger('reps')->nullable();
            $table->decimal('weight', 6, 2)->nullable(); // kg o lb, según prefieras
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_exercises');
    }
};
