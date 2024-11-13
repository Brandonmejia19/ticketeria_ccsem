<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    protected $casts = [
        'signos_vitales_gestor' => 'array',
        'estado_ambulancia' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'color',
        'tipo_ambulancia',
        //ATENCION PH
        'tipo_caso',
        'nu_caso',
        'te_alertante',
        'motivo_literal',
        'nombre_alertante',
        'nombres_paciente',
        'apellidos_paciente',
        'edad',
        'departamento_id',
        'distrito_id',
        'dirección_operador',
        'puntos_referencia',
        'notas_operador',
        'diagnostivo_presuntivo',
        'ambulancia_id',
        'prioridad',
        'recomendaciones_medico',
        'notas_medico',
        //AMBULANCIAS
        'dui',
        'sexo',
        'estado_ambulancia',
        'signos_vitales_gestor',
        'centro_destino',
        'centro_origen',
        'codigos_actuacion_ambu',
        'notas_gestor',
        //TRASLADO
        'centro_origen',
        'requerimientos_especiales',
        'medico_presenta',
        'numero_presenta',
        'medico_recibe',
        'numero_recibe',
        'signos_vitales_medicos',
        'location' => 'array',
    ];
}
