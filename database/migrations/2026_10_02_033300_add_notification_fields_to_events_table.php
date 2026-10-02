<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedSmallInteger('remind_minutes_before')->default(30)->after('all_day');
            $table->timestamp('notified_at')->nullable()->after('remind_minutes_before');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['remind_minutes_before', 'notified_at']);
        });
    }
};
