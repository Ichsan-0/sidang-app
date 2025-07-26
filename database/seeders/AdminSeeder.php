<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;


class AdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat role superadmin
        $role = Role::firstOrCreate(['name' => 'superadmin']);

        // 2. Buat user baru
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'), // ganti password jika perlu
            'no_induk' => '12345',
            'prodi_id' => 1, // isi sesuai id prodi
            // tambahkan kolom lain jika dibutuhkan
        ]);

        // 3. Assign role
        if (!$admin->hasRole($role)) {
            $admin->assignRole($role);
        }
    }
}