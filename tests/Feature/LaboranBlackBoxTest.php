<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lab;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class LaboranBlackBoxTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createLaboran()
    {
        return User::create([
            'username' => 'Test Laboran',
            'nim' => 'LABORAN001',
            'email' => 'laboran@example.com',
            'password' => bcrypt('password123'),
            'role' => 'laboran',
        ]);
    }

    private function createLab()
    {
        return Lab::create([
            'nama' => 'Lab Komputer 1',
            'pic' => 'Budi Laboran',
            'status' => 'tersedia'
        ]);
    }

    private function createPeminjamanPending()
    {
        $mahasiswa = User::create([
            'username' => 'Test Mhs',
            'nim' => '123123123',
            'email' => 'mhs@example.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        $lab = $this->createLab();

        return Peminjaman::create([
            'id_user' => $mahasiswa->id_user,
            'id_lab' => json_encode([strval($lab->id_lab)]),
            'tgl_mulai' => Carbon::tomorrow()->format('Y-m-d'),
            'tgl_selesai' => Carbon::tomorrow()->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'keterangan' => 'Pengujian oleh Laboran',
            'kebutuhan_alat' => '-',
            'daftar_nama' => json_encode([]),
            'pembimbing' => 'Dosen A',
            'email_pembimbing' => 'dosena@example.com',
            'ketua_kegiatan' => 'Ketua A',
            'kontak_ketua' => '08111',
            'status' => 'pending_laboran',
            'level' => '1'
        ]);
    }

    /**
     * Skenario 1: Halaman Login (Melakukan Login)
     */
    public function test_melakukan_login()
    {
        $laboran = $this->createLaboran();

        $response = $this->post('/login-web', [
            'login' => 'laboran@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($laboran);
    }

    /**
     * Skenario 2: Halaman Dashboard (Menampilkan Data Statistik)
     */
    public function test_menampilkan_data_statistik()
    {
        $laboran = $this->createLaboran();
        
        // Buat data pancingan agar statistik tidak kosong
        $this->createLab();

        $response = $this->actingAs($laboran)->get('/dashboard');

        $response->assertStatus(200);
        // Dashboard memuat statistik, kita periksa apakah view merender kata-kata statistik tertentu
        $response->assertSee('Status Peminjaman');
    }

    /**
     * Skenario 3: Halaman Riwayat Peminjaman (Menampilkan Detail Pengajuan)
     */
    public function test_menampilkan_detail_pengajuan()
    {
        $laboran = $this->createLaboran();
        $peminjaman = $this->createPeminjamanPending();

        $response = $this->actingAs($laboran)->get('/peminjaman/detail/' . $peminjaman->id);

        $response->assertStatus(200);
        $response->assertSee('Pengujian oleh Laboran');
        $response->assertSee('Jejak Persetujuan');
    }

    /**
     * Skenario 4: Halaman Riwayat Peminjaman (Menyetujui Pengajuan)
     */
    public function test_menyetujui_pengajuan_peminjaman()
    {
        $laboran = $this->createLaboran();
        $peminjaman = $this->createPeminjamanPending(); // Status awalnya pending_laboran, level = 1. Setelah laboran setuju, jadi approved

        $response = $this->actingAs($laboran)->post('/peminjaman/' . $peminjaman->id . '/approval-web', [
            'status' => 'approved'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'approved' // Karena level 1, setelah laboran langsung approved
        ]);
    }

    /**
     * Skenario 5: Halaman Riwayat Peminjaman (Menolak Pengajuan)
     */
    public function test_menolak_pengajuan_peminjaman()
    {
        $laboran = $this->createLaboran();
        $peminjaman = $this->createPeminjamanPending();

        $response = $this->actingAs($laboran)->post('/peminjaman/' . $peminjaman->id . '/approval-web', [
            'status' => 'rejected'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'rejected'
        ]);
    }

    /**
     * Skenario 6: Email Notifikasi (Menyetujui via email)
     */
    public function test_menyetujui_pengajuan_via_email()
    {
        $peminjaman = $this->createPeminjamanPending();

        // Buat signed URL persis seperti yang dikirim via email
        $url = URL::signedRoute('peminjaman.email_approve', ['id' => $peminjaman->id]);

        $response = $this->get($url);

        $response->assertSee('Berhasil');
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => 'approved'
        ]);
    }

    /**
     * Skenario 7: Halaman Data Lab (Menambahkan Data Ruang Lab)
     */
    public function test_menambahkan_data_ruang_lab()
    {
        $laboran = $this->createLaboran();

        $response = $this->actingAs($laboran)->post('/labs', [
            'nama' => 'Lab Baru 123',
            'pic' => 'Bapak PIC',
            'status' => 'tersedia'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('lab', [
            'nama' => 'Lab Baru 123',
            'pic' => 'Bapak PIC',
            'status' => 'tersedia'
        ]);
    }

    /**
     * Skenario 8: Halaman Data Aset (Menambahkan Data Aset Lab)
     */
    public function test_menambahkan_data_aset_lab()
    {
        $laboran = $this->createLaboran();

        $response = $this->actingAs($laboran)->post('/assets', [
            'nama_asset' => 'Proyektor Epson',
            'posisi_asset' => 'Meja Depan'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('assets', [
            'nama_asset' => 'Proyektor Epson',
            'posisi_asset' => 'Meja Depan'
        ]);
    }

    /**
     * Skenario 9: Halaman Jadwal Kuliah (Menambahkan Jadwal Kuliah)
     */
    public function test_menambahkan_jadwal_kuliah()
    {
        $laboran = $this->createLaboran();
        $lab = $this->createLab();

        $response = $this->actingAs($laboran)->post('/jadwal', [
            'id_lab' => $lab->id_lab,
            'mata_kuliah' => 'Jaringan Komputer',
            'dosen' => 'Bapak Dosen',
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('jadwal_kuliah', [
            'mata_kuliah' => 'Jaringan Komputer',
            'dosen' => 'Bapak Dosen',
            'hari' => 'Senin'
        ]);
    }

    /**
     * Skenario 10: Halaman Dashboard (Melakukan Logout)
     */
    public function test_melakukan_logout()
    {
        $laboran = $this->createLaboran();
        
        $response = $this->actingAs($laboran)->post('/logout-web');

        $response->assertRedirect('/dashboard');
        $this->assertGuest();
    }
}
