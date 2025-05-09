<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Departamento extends Model
{
    protected $fillable = [
        'name',
    ];

    public function distritos(): HasMany
    {
        return $this->hasMany(Distrito::class);
    }
    public function caso(): HasMany
    {
        return $this->hasMany(Caso::class);
    }
}
