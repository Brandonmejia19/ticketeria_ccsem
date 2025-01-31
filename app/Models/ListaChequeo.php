<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaChequeo extends Model
{
    protected $fillable = [
        'fecha',
        'turno',
        'AEM',
        'nivel_combustible',
        'cupones',
        'cantidad_cupones',
        'entrega_factura',
        'cantidad_factura',
        'numeros_factura',
        'detalles_daños',
        'aem_entrega',
        'entrega_firma',
        'aem_recibe',
        'recibe_firma',
        'observaciones'
    ];
}
