<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'identificacion';

    public function citas(){
        return $this->hasMany(Respuesta::class, 'identificacion', 'id');
    }

    public function citasCount(){
        return $this->citas()->count();
    }
}
