<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lab;
use App\Models\Peminjaman;
use Carbon\Carbon;

class KajurWadirBlackBoxTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createRoleUser($role)
    {
        return User::create([
            'username' => 'Test ' . ucfirst($role),
            'nim' => strtoupper($role) . '001',
            'email' => $role . '@example.com',
            'password' => bcrypt('password123'),
            'role' => $role,
        ]);
    }

    private function createLab()
    {
        return Lab::create([
            'nama' => 'Lab Komputer 3',
            'pic' => 'Budi Laboran',
            'status' => 'tersedia'
        ]);
    }

    private function createPeminjaman($status, $level = '3')
    {
        $mahasiswa = $this->createRoleUser('mahasiswa');
        $lab = $this->createLab();

        return Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Pengujian Berjenjang',
            'kebutuhan_alat' => '-',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen Pembimbing',
            'email_pembimbing' => 'dosen@example.com',
            'ketua_kegiatan' => 'Ketua A',
            'kontak_ketua' => '08111',
            'status' => $status,
            'level' => $level
        ]);
    }

    /**
     * Skenario 1: Approval Level 2 (Kajur)
     */
    public function test_approval_level_2_oleh_kajur()
    {
        $kajur = $this->createRoleUser('kajur');
        $peminjaman = $this->createPeminjaman('pending_kajur', '3'); // Level 3 pengajuan

        $response = $this->actingAs($kajur)->post('/peminjaman/' . $peminjaman->id . '/approval-web', [
            'status' => 'approved'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Status karena ini level 3, setelah disetujui Kajur akan naik jadi pending_wadir
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'pending_wadir'
        ]);
    }

    /**
     * Skenario 2: Approval Final (Wadir)
     */
    public function test_approval_final_oleh_wadir()
    {
        $wadir = $this->createRoleUser('wadir');
        $peminjaman = $this->createPeminjaman('pending_wadir', '3'); // Sudah sampai wadir

        $response = $this->actingAs($wadir)->post('/peminjaman/' . $peminjaman->id . '/approval-web', [
            'status' => 'approved'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Setelah disetujui Wadir, status menjadi final (approved)
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'approved'
        ]);
    }

    /**
     * Skenario 3: Tolak Pengajuan
     */
    public function test_tolak_pengajuan_peminjaman()
    {
        $kajur = $this->createRoleUser('kajur');
        $peminjaman = $this->createPeminjaman('pending_kajur', '2'); // Pengajuan sampai Kajur

        // Kajur menolak
        $response = $this->actingAs($kajur)->post('/peminjaman/' . $peminjaman->id . '/approval-web', [
            'status' => 'rejected'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Status langsung menjadi rejected, tidak akan berlanjut ke atasnya
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'rejected'
        ]);
    }
}
