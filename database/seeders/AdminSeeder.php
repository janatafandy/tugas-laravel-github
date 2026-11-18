<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Hapus dulu yang lama biar gak double
        DB::table('employees')->where('email', 'admin@equityworld.com')->delete();

        // Insert data baru
        DB::table('employees')->insert([
            'name' => 'Admin',
            'email' => 'admin@equityworld.com',
            'password' => Hash::make('password'),
            'position' => 'Admin',
            'department' => 'HRD',
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
