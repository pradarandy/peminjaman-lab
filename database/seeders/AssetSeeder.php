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
            'nama_asset' => 'Proyektor Epson',
            'posisi_asset' => 'Lab Pemrograman',
        ]);

        Asset::create([
            'nama_asset' => 'Oscilloscope',
            'posisi_asset' => 'Lab Elektronika',
        ]);

        Asset::create([
            'nama_asset' => 'Router Cisco',
            'posisi_asset' => 'Lab Jaringan',
        ]);
    }
}
