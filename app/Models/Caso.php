<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    protected $fillable = [
        //ATENCION PH
        'tipo_caso',
        'nu_caso',
        'te_alertante',
        'motivo_literal',
        'nombre_alertante',
        'nombres_paciente',
        'apellidos_paciente',
        'edad',
        'sexo',
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
        'estado_ambulancia',
        'signos_vitales_gestor',
        'centro_destino',
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
    ];
}
