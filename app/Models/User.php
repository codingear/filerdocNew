<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;
use App\Models\User as Users;
use App\Models\History;
use App\Models\Datasheet;
use App\Models\Inquiry;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
           'id'         => Where::class,
           'name'       => Like::class,
           'email'      => Like::class,
           'updated_at' => WhereDateStartEnd::class,
           'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function getFullNameAttribute(){
        return $this->name.' '.$this->last_name.' '.$this->mother_last_name;
    }

    public function patients(){
        return $this->hasMany(Users::class,'doctor_id','id');
    }

    public function inquiries(){
        return $this->hasMany(Inquiry::class,'user_id','id')->orderBy('created_at','desc');
    }

    public function getInquiryCount(){
        return $this->inquiries()->count();
    }

    public function history(){
        return $this->hasOne(History::class,'user_id','id');
    }

    public function datasheet(){
        return $this->hasOne(Datasheet::class,'user_id','id');
    }
}
