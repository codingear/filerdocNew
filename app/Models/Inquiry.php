<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Inquiry extends Model
{
    use HasFactory, AsSource;
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getReasonCutAttribute(){
        if(strlen($this->reason) > 40){
            return substr($this->reason,0,40).'...';
        } else {
            return $this->reason;
        }
        
    }
}
