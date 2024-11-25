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
        Schema::create('llamadas', function (Blueprint $table) {
            /** 'medico_aph',
        'hora_creacion',
        'telefono_alertante',
        'nombre_alertante',
        'motivo_literal',
        'tipo_caso',
        'descripcion_caso',
        'lugar_origen',
        'lugar_destino',
        'cod_ambulancia',
        'opcion_pertinente' */
            $table->id();
            $table->dateTime('hora_creacion');
            $table->text('telefono_alertante');
            $table->text('motivo_literal');
            $table->text('tipo_caso');
            $table->text('descripcion_caso');
            $table->text('lugar_origen');
            $table->text('lugar_destino');
            $table->text('cod_ambulancia');
            $table->text('opcion_pertinente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamadas');
    }
};
