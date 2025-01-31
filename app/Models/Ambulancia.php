<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulancia extends Model
{
    protected $fillable = [
        'placa',
        'unidad',
        'kilometraje',
        'cyrus',
        'estado_ambulancia',
        'camaras',
        'radio',
    ];

    public function llamada()
    {
        return $this->belongsTo(Llamadas::class);
    }
}
