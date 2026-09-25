<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('command_logs', function (Blueprint $table) {
            $table->id();
            $table->text('command'); // comando exacto que se pidió ejecutar
            $table->string('working_directory')->nullable(); // carpeta donde se ejecutó
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado', 'ejecutado', 'fallido'])->default('pendiente');
            $table->enum('risk_level', ['bajo', 'medio', 'alto'])->default('bajo'); // alto = requiere doble confirmación
            $table->longText('output')->nullable(); // salida del comando
            $table->integer('exit_code')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('command_logs');
    }
};
