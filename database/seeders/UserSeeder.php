<?php

namespace Database\Seeders;

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
                // Admin
                $admin = User::create([
                    'nama' => 'Admin Utama',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                ]);
                $admin->assignRole('admin');
        
                // Resepsionis
                $resepsionis = User::create([
                    'nama' => 'Resepsionis Satu',
                    'email' => 'resepsionis@example.com',
                    'password' => Hash::make('password'),
                ]);
                $resepsionis->assignRole('resepsionis');
        
                // Customer
                $customer = User::create([
                    'nama' => 'Pelanggan Pertama',
                    'email' => 'customer@example.com',
                    'password' => Hash::make('password'),
                ]);
                $customer->assignRole('customer');
    }
}
