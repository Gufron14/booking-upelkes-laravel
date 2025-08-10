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
                    'nama' => 'Admin Upelkes',
                    'email' => 'admin@upelkes.com',
                    'password' => Hash::make('password'),
                    'nip' => '123456789000',
                ]);
                $admin->assignRole('admin');
        
                // Resepsionis
                $resepsionis = User::create([
                    'nama' => 'Kepala UPTD',
                    'email' => 'kepalauptd@upelkes.com',
                    'password' => Hash::make('password'),
                    'nip' => '123456789001',
                ]);
                $resepsionis->assignRole('resepsionis');
        
                // Customer
                $customer = User::create([
                    'nama' => 'Pelanggan Pertama',
                    'email' => 'customer@example.com',
                    'password' => Hash::make('password'),
                    'nip' => '123456789002',
                ]);
                $customer->assignRole('customer');
    }
}
