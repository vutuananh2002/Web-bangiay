<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';
    protected $fillable = [
        'email',
        'token',
    ];

    /**
     * Check if the token is expire.
     * 
     * @return bool
     */
    public function isExpire()
    {
        return Carbon::parse($this->updated_at)->addHour()->isPast();
    }
}
