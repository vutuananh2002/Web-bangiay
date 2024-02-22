<?php

namespace App\Models;

use App\Traits\UserAuthorization;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, UserAuthorization;

    protected $table = 'users';
    protected $permissionList = null;
    protected $with = [
        'avatar',
        'customer',
        'admin',
        'roles'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the user's avatars.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function avatar()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function customer() 
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) return $this->where('id', $value)->firstOrFail();
        return $this->where('username', $value)->firstOrFail();
    }
}
