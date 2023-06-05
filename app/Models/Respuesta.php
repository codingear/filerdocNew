<?php

namespace App\Models;
use App\Models\Pregunta_directa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'respuesta';

    public function res_direct()
    {
        return $this->hasOne(Pregunta_directa::class, 'id', 'tabla_id');
    }
}
