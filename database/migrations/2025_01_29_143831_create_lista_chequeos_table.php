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
        Schema::create('lista_chequeos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->text('turno');
            $table->text('AEM');
            $table->text('nivel_combustible');
            $table->text('cupones');
            $table->text('cantidad_cupones');
            $table->text('entrega_factura');
            $table->text('cantidad_factura');
            $table->text('numeros_factura');
            $table->text('detalles_daños');
            $table->text('aem_entrega');
            $table->text('entrega_firma');
            $table->text('aem_recibe');
            $table->text('recibe_firma');
            $table->text('observaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_chequeos');
    }
};
