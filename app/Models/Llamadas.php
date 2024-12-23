<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Llamadas extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario',
        'llamada_correlativo',
        'caso_id',
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
        'opcion_pertinente',
        'opcion_informativa',
        'lugar_solicitante',
        'diagnostico_traslado',
        'aplica_traslado',
        'justificacion_traslado',
        'motivo_traslado'

    ];
    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function casos2()
    {
        return $this->belongsToMany(Caso::class, 'caso_llamada', 'llamada_id', 'caso_id')
            ->withTimestamps();
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
            $numeroSecuencial = str_pad($contador, 7, '0', STR_PAD_LEFT);
            $correlativo = "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";
            $llamada->update([
                'llamada_correlativo' => $correlativo,
            ]);

        });
    }

}
