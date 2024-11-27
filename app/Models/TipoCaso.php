<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCaso extends Model
{
    protected $fillable = [
        'name',   
    ];
    public function llamada()
    {
        return $this->hasMany(Llamadas::class);
    }
}
