<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $fillable = [
        'name',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
