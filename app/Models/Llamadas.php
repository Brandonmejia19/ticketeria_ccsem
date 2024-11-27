<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Llamadas extends Model
{
    protected $fillable = [
        'llamada_correlativo',
        'medico_aph',
        'hora_creacion',
        'telefono_alertante',
        'nombre_alertante',
        'motivo_literal',
        'tipo_caso',
        'descripcion_caso',
        'lugar_origen',
        'lugar_destino',
        'cod_ambulancia',
        'opcion_pertinente'

    ];
    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    //GENERADOR DE CORRELATIVOS
    protected static function boot()
    {
        parent::boot();

        // Evento después de crear
        static::created(function ($llamada) {
            $fecha = now()->format('dmY'); // Fecha en formato "dmyyyy" (día, mes, año)
            $tipoCasoInicial = strtoupper(substr($llamada->tipo_caso, 0, 1)); // Inicial del tipo de caso en mayúscula

            // Contar las llamadas creadas en el día actual
            $contador = self::whereDate('created_at', now()->format('Y-m-d'))->count();

            // Generar el número secuencial de 6 dígitos
            $numeroSecuencial = str_pad($contador, 6, '0', STR_PAD_LEFT);

            // Generar el correlativo
            $correlativo = "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";

            // Actualizar el campo `llamada_correlativo` y guardar
            $llamada->update([
                'llamada_correlativo' => $correlativo,
            ]);
        });
    }

}
