<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'name' => 'muhammadabdullaharridho',
            'email' => 'abdullaharridho03@gmail.com',
            'password' => Hash::make('12345678'),
            'tipeuser' => 'admin',
            'otp' => '123456',
            'expires_at' => '2025-07-25 03:41:27',
        ]);
    }
}
