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
        Schema::create('casos', function (Blueprint $table) {
            $table->id();
              // Campos de atención PH
              $table->string('tipo_caso')->nullable();
              $table->string('nu_caso')->nullable();
              $table->string('te_alertante')->nullable();
              $table->string('motivo_literal')->nullable();
              $table->string('nombre_alertante')->nullable();
              $table->string('nombres_paciente')->nullable();
              $table->string('apellidos_paciente')->nullable();
              $table->integer('edad')->nullable();
              $table->enum('sexo', ['M', 'F'])->nullable();
              $table->text('departamento_id')->nullable();
              $table->text('distrito_id')->nullable();
              $table->string('dirección_operador')->nullable();
              $table->text('puntos_referencia')->nullable();
              $table->text('notas_operador')->nullable();
              $table->string('diagnostivo_presuntivo')->nullable();
              $table->text('ambulancia_id')->nullable();
              $table->string('prioridad')->nullable();
              $table->text('recomendaciones_medico')->nullable();
              $table->text('notas_medico')->nullable();

              // Campos de ambulancias
              $table->string('dui')->nullable();
              $table->string('estado_ambulancia')->nullable();
              $table->text('signos_vitales_gestor')->nullable();
              $table->string('centro_destino')->nullable();
              $table->string('codigos_actuacion_ambu')->nullable();
              $table->text('notas_gestor')->nullable();

              // Campos de traslado
              $table->string('centro_origen')->nullable();
              $table->text('requerimientos_especiales')->nullable();
              $table->string('medico_presenta')->nullable();
              $table->string('numero_presenta')->nullable();
              $table->string('medico_recibe')->nullable();
              $table->string('numero_recibe')->nullable();
              $table->text('signos_vitales_medicos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
