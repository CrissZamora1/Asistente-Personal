<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('note')->nullable();
            $table->timestamp('remind_at');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule')->nullable(); // ej. "daily", "weekly:mon,wed", "monthly:15"
            $table->timestamp('last_notified_at')->nullable();
            $table->boolean('is_critical')->default(false); // usa alarma nativa, no solo push
            $table->timestamps();

            $table->index('remind_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
