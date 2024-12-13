<?php

namespace App\Models;

use Filament\Forms\Components\HasManyRepeater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Audit;
use Illuminate\Support\HtmlString;
class Caso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $casts = [
        'signos_vitales' => 'array',
        'estado_recurso' => 'array',
    ];

    protected $fillable = [
        //GENERAL
        'user_id',
        'usuario',
        'llamada_asociada',
        'correlativo_caso',
        'tipo_caso',
        //OPERADOR
        'nombres_paciente',
        'apellidos_paciente',
        'edad',
        'edad_complemento',
        'sexo',
        'dirección',
        'puntos_referencia',
        'departamento',
        'distrito',
        'tap',
        'tap1',
        'plan_experto',
        'prioridad',
        'antecedentes',
        'enfermedades',
        'asegurado',
        'institucion',
        'institucion_apoyo',
        'notas',
        //GESTOR
        'via_transporte',
        'tipo_unidad',
        'recurso_asignado',
        'estado_recurso',
        'notas_gestor_recurso',
        'unidad_salud_traslado',
        'unidad_salud_sugerido',
        'signos_vitales',
        'efectividad',
        'razon_noefectivo',
        'exclusion',
        'motivo_exclusion',
        'notas_gestor',
        //MEDICO
        'cie10',
        'juicio_clinico1',
        'juicio_clinico2',
        'juicio_clinico3',
        'condicion_paciente',
        'paciente_critico',
        'resolucion_atencion',
        'fallecimiento',
        'acta_defuncion',
        'medicina_legal',
        'retorno_origen',
        'entregado_destino',
        'nombre_recibio',
        'aceptado_destino',
        'notas_medicos',
        'llamada_id',
        'color',

    ];
    public function formatAuditFieldsForPresentation($field, Audit $record)
    {
        $fields = Arr::wrap($record->{$field});

        $formattedResult = '<ul>';

        foreach ($fields as $key => $value) {
            $formattedResult .= '<li>';
            $formattedResult .= match ($key) {
                'user_id' => '<strong>User</strong>: ' . User::find($record->{$field}['user_id'])?->name . '<br />',
                'title' => '<strong>Title</strong>: ' . (string) str($record->{$field}['title'])->title() . '<br />',
                'order' => '<strong>Order</strong>: ' . $record->{$field}['order'] . '<br />',
                'content' => '<strong>Content</strong>: ' . $record->{$field}['content'] . '<br />',
                default => ' - ',
            };
            $formattedResult .= '</li>';
        }

        $formattedResult .= '</ul>';

        return new HtmlString($formattedResult);
    }
    protected static function boot()
    {
        parent::boot();
        static::created(function ($caso) {
            $fecha = now()->format('dmYHis');
            $tipoCasoInicial = strtoupper(substr('tipo_caso', 0, 2));
            $contador = self::whereDate('created_at', now()->format('Y-m-d'))->count();
            $numeroSecuencial = str_pad($contador, 5, '0', STR_PAD_LEFT);
            $correlativo = "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";
            $caso->update([
                'correlativo_caso' => $correlativo,
            ]);

        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function llamadas()
    {
        return $this->hasMany(Llamadas::class);
    }
    public function llamadas2()
    {
        return $this->belongsToMany(Llamadas::class, 'caso_llamada', 'caso_id', 'llamada_id')
            ->withTimestamps();
    }
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }
    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class);
    }
    public function estadoscasos(): HasMany
    {
        return $this->hasMany(EstadoCaso::class, 'casos_id');
    }

}
