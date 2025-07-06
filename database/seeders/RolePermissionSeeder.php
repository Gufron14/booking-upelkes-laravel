<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Buat Role
                $admin = Role::firstOrCreate(['name' => 'admin']);
                $resepsionis = Role::firstOrCreate(['name' => 'resepsionis']);
                $customer = Role::firstOrCreate(['name' => 'customer']);
        
                // (Opsional) Buat permission jika ingin granular
                $permissions = [
                    'lihat layanan',
                    'buat booking',
                    'verifikasi booking',
                    'kelola layanan',
                    'kelola user',
                ];
        
                foreach ($permissions as $perm) {
                    Permission::firstOrCreate(['name' => $perm]);
                }
        
                // Assign permission ke role
                $admin->givePermissionTo(Permission::all());
                $resepsionis->givePermissionTo([
                    'lihat layanan',
                    'verifikasi booking',
                ]);
                $customer->givePermissionTo([
                    'lihat layanan',
                    'buat booking',
                ]);
    }
}
