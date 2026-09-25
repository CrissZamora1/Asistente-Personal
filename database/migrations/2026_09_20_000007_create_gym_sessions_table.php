<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_sessions', function (Blueprint $table) {
            $table->id();
            $table->date('session_date');
            $table->enum('type', ['pesas', 'resistencia', 'boxeo', 'mixto'])->default('pesas');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('body_weight', 5, 2)->nullable(); // métrica física opcional del día
            $table->timestamps();

            $table->index('session_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_sessions');
    }
};
