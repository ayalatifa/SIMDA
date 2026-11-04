<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIMDA',
            'email' => 'admin@simda.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Siswa SIMDA',
            'email' => 'siswa@simda.test',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}