<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'root@school.app',
            'password' => Hash::make('password'),
            'role' => Role::ROOT,
            'gender' => 'Male', // A default gender
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Head',
            'last_name' => 'Teacher',
            'email' => 'headteacher@school.app',
            'password' => Hash::make('password'),
            'role' => Role::HEADTEACHER,
            'gender' => 'Female', // A default gender
            'email_verified_at' => now(),
        ]);
    }
}
