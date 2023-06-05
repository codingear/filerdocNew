<?php

namespace App\Models;

use App\Models\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Datasheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'religion',
        'tutor',
        'socioeconomic',
        'city',
        'address',
        'cp',
        'gender',
        'blood_type',
        'nationality',
        'place_of_birth',
        'civil_status',
        'scholarship',
        'birthdate',
        'comments',
        'different_capacity',
        'state_id',
        'municipality_id',
        'location_id',
        'user_id',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function state(){
        return $this->hasOne(States::class,'id','state_id');
    }

    public function getAgeAttribute(){
        $date = Carbon::parse($this->birthdate);
        return Carbon::createFromDate($date)->diff(Carbon::now())->format('%y Años, %m meses %d días');
    }
}
