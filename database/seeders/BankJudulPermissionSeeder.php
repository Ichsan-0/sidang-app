<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BankJudulPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions untuk Bank Judul
        $permissions = [
            'bank-judul-view',
            'bank-judul-create',
            'bank-judul-edit',
            'bank-judul-delete',
            'rekomendasi-judul-view',
            'rekomendasi-judul-create',
            'rekomendasi-judul-edit',
            'rekomendasi-judul-delete',
            'rekomendasi-judul-select', // untuk mahasiswa memilih judul
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permission ke role
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superadmin->givePermissionTo($permissions);

        $adminProdi = Role::firstOrCreate(['name' => 'admin prodi']);
        $adminProdi->givePermissionTo([
            'bank-judul-view',
            'bank-judul-create',
            'bank-judul-edit',
            'bank-judul-delete',
            'rekomendasi-judul-view',
        ]);

        $dosen = Role::firstOrCreate(['name' => 'dosen']);
        $dosen->givePermissionTo([
            'bank-judul-view',
            'rekomendasi-judul-view',
            'rekomendasi-judul-create',
            'rekomendasi-judul-edit',
            'rekomendasi-judul-delete',
        ]);

        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $mahasiswa->givePermissionTo([
            'bank-judul-view',
            'rekomendasi-judul-view',
            'rekomendasi-judul-select',
        ]);
    }
}
