<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateNimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            // Jika role nya selain admin, atau jika ingin semua diubah
            // Buat NIM: 225730 + 4 digit angka random
            $random4Digits = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $newNim = '225730' . $random4Digits;
            
            $user->nim = $newNim;
            $user->save();
        }
    }
}
