<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class EstadoCaso extends Model
{
    protected $fillable = [
        'AR',
        'fecha_ar',
        'E',
        'fecha_e',
        'EL',
        'fecha_el',
        'EA',
        'fecha_ea',
        'EC',
        'fecha_ec',
        'EE',
        'fecha_ee',
        'ED',
        'fecha_ed',
        'D',
        'fecha_d',
        'notas',
        'nu_caso',
        'cod_ambu',
        'user_id',
        'caso_id',
    ];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class, 'casos_id');
    }

}
