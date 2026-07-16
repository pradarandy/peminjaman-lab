<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kembalikan akun randy22si menjadi mahasiswa
        $randy = \App\Models\User::where('email', 'randy22si@mahasiswa.pcr.ac.id')->first();
        if ($randy) {
            $randy->update(['role' => 'mahasiswa']);
        }

        // Buat akun Laboran (Kalab)
        \App\Models\User::updateOrCreate(
            ['email' => 'laboran@pcr.ac.id'],
            [
                'username' => 'Kepala Laboratorium',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'laboran',
            ]
        );

        // Buat akun Kajur
        \App\Models\User::updateOrCreate(
            ['email' => 'kajur@pcr.ac.id'],
            [
                'username' => 'Kepala Jurusan',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'kajur',
            ]
        );

        // Buat akun Wadir
        \App\Models\User::updateOrCreate(
            ['email' => 'wadir@pcr.ac.id'],
            [
                'username' => 'Wakil Direktur',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'wadir',
            ]
        );
    }
}
