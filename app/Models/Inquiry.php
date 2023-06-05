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
}
