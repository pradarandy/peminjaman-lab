<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Asset;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::create([
            'inisial' => 'BD',
            'nama' => 'Bapak Budi',
            'email_dosen' => 'budi@pcr.ac.id',
            'nama_asset' => 'Proyektor Epson',
            'posisi_asset' => 'Lab Pemrograman',
        ]);

        Asset::create([
            'inisial' => 'AN',
            'nama' => 'Ibu Andi',
            'email_dosen' => 'andi@pcr.ac.id',
            'nama_asset' => 'Oscilloscope',
            'posisi_asset' => 'Lab Elektronika',
        ]);

        Asset::create([
            'inisial' => 'CW',
            'nama' => 'Bapak Cipto',
            'email_dosen' => 'cipto@pcr.ac.id',
            'nama_asset' => 'Router Cisco',
            'posisi_asset' => 'Lab Jaringan',
        ]);
    }
}
