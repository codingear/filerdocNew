<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'capacity_suffers',
        'allergy_medicine',
        'family_history',
        'non_pathological_history',
        'pathological_history',
        'gynecological_history',
        'perinatal_history',
        'administered_vaccine',
        'archived',
        'user_id',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
