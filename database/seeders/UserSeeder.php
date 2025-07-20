<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'client_id' => null,
                'name' => 'Admin ERA',
                'email' => 'admin@era.test',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => null, // Anda bisa ubah sesuai ID client yang tersedia
                'name' => 'Supervisor Satu',
                'email' => 'supervisor@era.test',
                'role' => 'supervisor',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
