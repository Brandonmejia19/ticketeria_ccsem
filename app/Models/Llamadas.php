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
    public function tipocaso(): BelongsTo
    {
        return $this->belongsTo(TipoCaso::class);
    }
    //GENERADOR DE CORRELATIVOS
    protected static function boot()
    {
        parent::boot();
        static::created(function ($llamada) {
            $fecha = now()->format('dmYHis');
            $tipoCasoInicial = strtoupper(substr('Llamada', 0, 2));
            $contador = self::whereDate('created_at', now()->format('Y-m-d'))->count();
            $numeroSecuencial = str_pad($contador, 5, '0', STR_PAD_LEFT);
            $correlativo = "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";
            $llamada->update([
                'llamada_correlativo' => $correlativo,
            ]);

        });
    }

}
