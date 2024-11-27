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
}
