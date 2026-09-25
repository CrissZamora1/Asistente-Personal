<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('captures', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->enum('type', ['idea', 'nota', 'enlace', 'otro'])->default('idea');
            $table->boolean('is_processed')->default(false); // si ya se convirtió en tarea/nota
            $table->string('processed_into_type')->nullable(); // "task", "note", etc.
            $table->unsignedBigInteger('processed_into_id')->nullable();
            $table->timestamps();

            $table->index('is_processed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('captures');
    }
};
