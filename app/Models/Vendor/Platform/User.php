<?php

declare(strict_types=1);

namespace Orchid\Platform\Models;

use App\Orchid\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Orchid\Access\UserAccess;
use Orchid\Access\UserInterface;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Dashboard;
use App\Models\Inquiry;
use App\Models\Datasheet;
use App\Models\History;

class User extends Authenticatable implements UserInterface
{
    use Notifiable, UserAccess, AsSource, Filterable, Chartable, HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'last_name',
        'mother_last_name',
        'phone',
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

    /**
     * Throw an exception if email already exists, create admin user.
     *
     * @throws \Throwable
     */
    public static function createAdmin(string $name, string $email, string $password): void
    {
        throw_if(static::where('email', $email)->exists(), 'User exists');

        static::create([
            'name'        => $name,
            'email'       => $email,
            'password'    => Hash::make($password),
            'permissions' => Dashboard::getAllowAllPermission(),
        ]);
    }

    /**
     * @return UserPresenter
     */
    public function presenter()
    {
        return new UserPresenter($this);
    }

    public function getFullNameAttribute(){
        return $this->name.' '.$this->last_name.' '.$this->mother_last_name;
    }

    public function patients(){
        return $this->hasMany(User::class,'doctor_id','id');
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
