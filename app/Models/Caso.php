<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Audit;
use Illuminate\Support\HtmlString;
class Caso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
        'diagnostivo_presuntivo_operador',
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
    public function formatAuditFieldsForPresentation($field, Audit $record)
    {
        $fields = Arr::wrap($record->{$field});

        $formattedResult = '<ul>';

        foreach ($fields as $key => $value) {
            $formattedResult .= '<li>';
            $formattedResult .= match ($key) {
                'user_id' => '<strong>User</strong>: '.User::find($record->{$field}['user_id'])?->name.'<br />',
                'title' => '<strong>Title</strong>: '.(string) str($record->{$field}['title'])->title().'<br />',
                'order' => '<strong>Order</strong>: '.$record->{$field}['order'].'<br />',
                'content' => '<strong>Content</strong>: '.$record->{$field}['content'].'<br />',
                default => ' - ',
            };
            $formattedResult .= '</li>';
        }

        $formattedResult .= '</ul>';

        return new HtmlString($formattedResult);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
