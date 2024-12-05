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
        Schema::create('signos_vitales', function (Blueprint $table) {
            $table->id();
            $table->string('ta')->nullable(); // Presión arterial
            $table->string('fc')->nullable(); // Frecuencia cardíaca
            $table->string('pr')->nullable(); // Pulso
            $table->float('temp')->nullable(); // Temperatura
            $table->float('sat_o2')->nullable(); // Saturación de oxígeno
            $table->float('hgt')->nullable(); // Glucosa
            $table->float('sg')->nullable();  // Signo G
            $table->float('str')->nullable(); // STR
            $table->string('estado')->nullable(); // Estado E/I
            $table->timestamp('fecha_hora')->nullable(); // Fecha y hora
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signos_vitales');
    }
};
