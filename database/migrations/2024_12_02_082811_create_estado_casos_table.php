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
        Schema::create('estado_casos', function (Blueprint $table) {
            $table->id();
            // Campos principales
            $table->string('AR')->nullable();
            $table->dateTime('fecha_ar')->nullable();
            $table->string('E')->nullable();
            $table->dateTime('fecha_e')->nullable();
            $table->string('EL')->nullable();
            $table->dateTime('fecha_el')->nullable();
            $table->string('EA')->nullable();
            $table->dateTime('fecha_ea')->nullable();
            $table->string('EC')->nullable();
            $table->dateTime('fecha_ec')->nullable();
            $table->string('EE')->nullable();
            $table->dateTime('fecha_ee')->nullable();
            $table->string('ED')->nullable();
            $table->dateTime('fecha_ed')->nullable();
            $table->string('D')->nullable();
            $table->dateTime('fecha_d')->nullable();

            // Notas y otros datos
            $table->text('notas')->nullable();
            $table->string('nu_caso')->unique();
            $table->string('cod_ambu')->nullable();

            // Relación con usuarios
            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_casos');
    }
};
