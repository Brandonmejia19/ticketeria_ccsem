<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id();

            // GENERAL
            $table->foreignId(column: 'user_id')->constrained()->onDelete('cascade');
            $table->text('usuario')->constrained()->onDelete('cascade');
            $table->foreignId('llamada_id')->nullable()->constrained()->onDelete('set null');
            $table->text('llamada_asociada')->nullable()->constrained()->onDelete('set null');
            $table->string('correlativo_caso')->unique();

            // OPERADOR
            $table->string('nombres_paciente');
            $table->string('apellidos_paciente');
            $table->integer('edad')->nullable();
            $table->string('edad_complemento')->nullable();
            $table->string('sexo' )->nullable();
            $table->text('dirección');
            $table->text('puntos_referencia')->nullable();
            $table->string('departamento');
            $table->string('distrito');
            $table->string('tap')->nullable();
            $table->string('tap1')->nullable();
            $table->string('plan_experto')->nullable();
            $table->string('prioridad');
            $table->text('antecedentes')->nullable();
            $table->text('enfermedades')->nullable();
            $table->boolean('asegurado');
            $table->string('institucion')->nullable();
            $table->string('institucion_apoyo')->nullable();
            $table->text('notas')->nullable();

            // GESTOR
            $table->string('via_transporte')->nullable();
            $table->string('tipo_unidad')->nullable();
            $table->string('recurso_asignado')->nullable();
            $table->string('estado_recurso')->nullable();
            $table->text('notas_gestor_recurso')->nullable();
            $table->string('unidad_salud_traslado')->nullable();
            $table->string('unidad_salud_sugerido')->nullable();
            $table->json('signos_vitales')->nullable();
            $table->string('efectividad')->nullable();
            $table->string('razon_noefectivo')->nullable();
            $table->boolean('exclusion')->nullable();
            $table->string('motivo_exclusion')->nullable();
            $table->text('notas_gestor')->nullable();

            // MÉDICO
            $table->string('cie10')->nullable();
            $table->string('condicion_paciente')->nullable();
            $table->boolean('paciente_critico')->nullable();
            $table->string('resolucion_atencion')->nullable();
            $table->boolean('fallecimiento')->nullable();
            $table->boolean('acta_defuncion')->nullable();
            $table->boolean('medicina_legal')->nullable();
            $table->boolean('retorno_origen')->nullable();
            $table->boolean('entregado_destino')->nullable();
            $table->string('nombre_recibio')->nullable();
            $table->boolean('aceptado_destino')->nullable();
            $table->text('notas_medicos')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casos');
    }
}

