<?php

namespace Database\Seeders;

use App\Enums\AccountStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
        // Tai khoan dang nhap admin day
        User::insert([
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123aA@xcv'),
                'is_verified' => true,
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'status' => AccountStatusEnum::Active,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ],
        );
    }
}


// run:" php artisan serve "