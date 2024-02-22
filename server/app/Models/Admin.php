<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';
    protected $fillable = [
        'name',
        'phone_number',
        'description'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
