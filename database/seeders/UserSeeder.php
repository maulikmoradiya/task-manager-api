<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => Hash::make('password'),
            'role' => 'Manager',
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Developer One',
            'email' => 'dev1@test.com',
            'password' => Hash::make('password'),
            'role' => 'Developer',
            'team_id' => 1
        ]);

        User::create([
            'name' => 'Developer Two',
            'email' => 'dev2@test.com',
            'password' => Hash::make('password'),
            'role' => 'Developer',
            'team_id' => 2
        ]);
    }
}
