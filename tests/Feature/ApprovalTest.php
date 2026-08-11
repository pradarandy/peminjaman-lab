<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Peminjaman;

class ApprovalTest extends TestCase
{

    public function test_laboran_can_approve_peminjaman()
    {
        // 1. Buat User Mahasiswa
        $mahasiswa = User::create([
            'username' => 'mahasiswa1',
            'email' => 'mhs1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa'
        ]);

        // 2. Buat User Laboran
        $laboran = User::create([
            'username' => 'laboran1',
            'email' => 'lab1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'laboran'
        ]);

        // 3. Buat Data Peminjaman status pending_laboran level 1
        $peminjaman = Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => ['1'],
            'kebutuhan_alat' => '-',
            'tgl_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Praktikum',
            'daftar_nama' => [(string)$mahasiswa->id_user],
            'pembimbing' => 'Bapak Dosen',
            'ketua_kegiatan' => 'Mhs 1',
            'kontak_ketua' => '081',
            'level' => '1',
            'status' => 'pending_laboran'
        ]);

        // 4. Laboran melakukan persetujuan (approve)
        $response = $this->actingAs($laboran)->post("/peminjaman/{$peminjaman->id}/approval-web", [
            'status' => 'approved'
        ]);

        // 5. Cek apakah status berubah menjadi approved (karena level 1)
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'approved'
        ]);
        
        $this->assertDatabaseHas('approval', [
            'id_peminjaman' => $peminjaman->id,
            'status' => 'approved'
        ]);
    }
}
