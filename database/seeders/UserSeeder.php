<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'ADMIN001',
            'role' => 'admin',
            'jabatan' => 'Administrator Sistem',
            'unit_kerja' => 'Dinas Komunikasi dan Informatika',
        ]);

        // Create supervisor user
        User::create([
            'name' => 'Ahmad Supervisor',
            'email' => 'supervisor@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'SUP001',
            'role' => 'supervisor',
            'jabatan' => 'Kepala Bidang',
            'unit_kerja' => 'Dinas Komunikasi dan Informatika',
        ]);

        // Create finance user
        User::create([
            'name' => 'Siti Finance',
            'email' => 'finance@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'FIN001',
            'role' => 'finance',
            'jabatan' => 'Analis Keuangan',
            'unit_kerja' => 'Badan Keuangan Daerah',
        ]);

        // Create verifikator user
        User::create([
            'name' => 'Budi Verifikator',
            'email' => 'verifikator@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'VER001',
            'role' => 'verifikator',
            'jabatan' => 'Verifikator Perjalanan Dinas',
            'unit_kerja' => 'Inspektorat',
        ]);

        // Create employee users
        User::create([
            'name' => 'Rahmat Employee',
            'email' => 'rahmat@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'EMP001',
            'role' => 'employee',
            'jabatan' => 'Staf Programming',
            'unit_kerja' => 'Dinas Komunikasi dan Informatika',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@bantaeng.go.id',
            'password' => Hash::make('password'),
            'nip' => 'EMP002',
            'role' => 'employee',
            'jabatan' => 'Staf Desain Grafis',
            'unit_kerja' => 'Dinas Komunikasi dan Informatika',
        ]);
    }
}
