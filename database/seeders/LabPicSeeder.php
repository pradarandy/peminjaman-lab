<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lab;

class LabPicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Lab::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $labsData = [
            ['nama' => 'Lab Data & Business Analyst 319', 'pic' => 'Nessa Chairani Bemin, S.S.T.'],
            ['nama' => 'Lab Programming I (225)', 'pic' => 'Rezky Kurniawan, S.Tr.Kom.'],
            ['nama' => 'Lab Game & Animation (313)', 'pic' => 'Rezky Kurniawan, S.Tr.Kom.'],
            ['nama' => 'Lab Web Programming I (316)', 'pic' => 'Rezky Kurniawan, S.Tr.Kom.'],
            ['nama' => 'Lab Mobile Programming I (317)', 'pic' => 'Aida Kamila, S.S.T.'],
            ['nama' => 'Lab Multimedia Studio (352)', 'pic' => 'Aida Kamila, S.S.T.'],
            ['nama' => 'Lab Big Data & AI (320)', 'pic' => 'Ahmad Fauzi, S.Tr.Kom.'],
            ['nama' => 'Lab Database (325)', 'pic' => 'Ahmad Fauzi, S.Tr.Kom.'],
            ['nama' => 'Lab Web Programming II (330)', 'pic' => 'Ahmad Fauzi, S.Tr.Kom.'],
            ['nama' => 'Lab Computer Networking I (324)', 'pic' => 'Dwi Listiyanti, S.S.T.'],
            ['nama' => 'Lab Game Development (328)', 'pic' => 'Dwi Listiyanti, S.S.T.'],
            ['nama' => 'Lab Computer Networking II (281)', 'pic' => 'Harumin, S.S.T.'],
            ['nama' => 'Lab Operating System (283)', 'pic' => 'Harumin, S.S.T.'],
            ['nama' => 'Lab Mobile Programming III (151)', 'pic' => 'Irgi Yoga Pangestu, S.Tr.Kom.'],
            ['nama' => 'Lab LX (152)', 'pic' => 'Irgi Yoga Pangestu, S.Tr.Kom.'],
            ['nama' => 'Lab Soft Computing II (156)', 'pic' => 'Dara Framini, S.Tr.T.'],
            ['nama' => 'Lab Mobile Programming II (252)', 'pic' => 'Dara Framini, S.Tr.T.'],
            ['nama' => 'Lab Soft Computing I (256)', 'pic' => 'Dara Framini, S.Tr.T.'],
            ['nama' => 'Lab Programming II (282)', 'pic' => 'Thesa Nabila Balqis .P, S.Tr.T.'],
            ['nama' => 'Lab IoT & Embedded System (284)', 'pic' => 'Thesa Nabila Balqis .P, S.Tr.T.'],
        ];

        foreach ($labsData as $data) {
            Lab::create([
                'nama' => $data['nama'],
                'pic' => $data['pic'],
                'status' => 'tersedia', // default status
            ]);
        }
    }
}
