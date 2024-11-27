<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroSanitario extends Model
{
    protected $fillable = [
        'name',
        'direction',
    ];

    public function llamada()
    {
        return $this->belongsTo(Llamadas::class);
    }
}
