<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labs = \App\Models\Lab::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $mataKuliah = [
            'Algoritma Pemrograman', 'Struktur Data', 'Jaringan Komputer', 'Basis Data',
            'Rekayasa Perangkat Lunak', 'Pemrograman Web', 'Sistem Operasi', 'Kecerdasan Buatan',
            'Grafika Komputer', 'Keamanan Sistem', 'Internet of Things', 'Mobile Programming',
            'Machine Learning', 'Data Mining', 'Desain UI/UX', 'Cloud Computing',
            'Game Development', 'Computer Vision', 'Pemrograman Berorientasi Objek', 'Kalkulus'
        ];

        $dosenInitials = [
            'A.B., M.Kom.', 'B.C., M.T.', 'C.D., S.T., M.Eng.', 'D.E., Ph.D.',
            'E.F., M.Cs.', 'F.G., M.Sc.', 'G.H., M.T.', 'H.I., S.Kom., M.Kom.',
            'I.J., M.IT.', 'J.K., Dr.Eng.'
        ];

        // Opsional: Hapus jadwal lama jika ingin mereset setiap seed
        // \App\Models\JadwalKuliah::truncate();

        foreach ($labs as $lab) {
            foreach ($days as $day) {
                $randomMk = $mataKuliah[array_rand($mataKuliah)];
                $randomDosen = $dosenInitials[array_rand($dosenInitials)];

                \App\Models\JadwalKuliah::create([
                    'id_lab' => $lab->id_lab,
                    'mata_kuliah' => $randomMk,
                    'dosen' => $randomDosen,
                    'hari' => $day,
                    'jam_mulai' => '07:00',
                    'jam_selesai' => '11:00'
                ]);
            }
        }
    }
}
