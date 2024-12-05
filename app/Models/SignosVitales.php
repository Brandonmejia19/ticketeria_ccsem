<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SignosVitales extends Model
{
    protected $fillable = [
        'ta', 'fc', 'pr', 'temp', 'sat_o2', 'hgt', 'sg', 'str', 'estado', 'fecha_hora'
    ];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

}
